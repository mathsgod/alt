<?php
namespace ALT;

use R\Psr7\Stream;

class MasterPage
{
    private $route;
    private $_template;
    private $_twig = [];

    public function assign($name, $value)
    {
        $this->data[$name] = $value;
    }


    public function __invoke($request, $response)
    {
        $data=$this->data;
     
        // get the data
        $data["lang"] = \My::Language();
        if (\App::Config("user", "development")) {
            $data["development"] = "development";
        }
        $data["setting"] = json_decode(\App::User()->setting, true);

        if ($data["setting"]["control-sidebar"]) {
            $data["sidebar"] = $data["setting"]["control-sidebar"];
        } else {
            $data["sidebar"] = "dark";
        }

        if (($skin = \App::User()->skin()) instanceof Skin) {
            $data["skin"] = $skin->setting["base"];
        } else {
            $data["skin"] = $skin;
        }

        $data["user"] = [];
        $data["user"] = (array)\App::User();
        $data["user"]["name"] = (string)\App::User();
        $data["user"]["usergroup"] = \App::User()->UserGroup()->implode(",");

        $data["system"] = [];
        $data["system"]["version"] = \App::Version();

        $data["copyright"]["url"] = \App::Config("user", "copyright-url");
        $data["copyright"]["year"] = \App::Config("user", "copyright-year");
        $data["copyright"]["name"] = \App::Config("user", "copyright-name");

        $data["company"] = \App\Config::_("company");
        $data["logo-mini"] = \App\Config::_("logo-mini");
        $data["logo"]=\App\Config::_('logo');
        $data["base"] = \App\System::BasePath();
        

        $custom_header = \App::TPL("AdminLTE/custom-header.html");
        $data["custom_header"] = \App::TPL("AdminLTE/custom-header.html")->getOutputContent();

        $sidebar_menu = [];
        $ms = [];
        foreach (\App::Module() as $m) {
            if ($m->hide) {
                continue;
            }
            if ($m->group) {
                $ms[$m->group][] = $m;
            } else {
                $ms[] = $m;
            }
        }

        $group_icon = [];
        if ($ini = \App::Path("icon.ini")) {
            $group_icon = array_merge($group_icon, parse_ini_file($ini));
        }
        if ($ini = \App::Path("pages/icon.ini")) {
            $group_icon = array_merge($group_icon, parse_ini_file($ini));
        }

        $path=$request->getUri()->getPath();
        if($path[0]=="/")$path=substr($path,1);
        $current_module=$request->getAttribute("module");
        foreach ($ms as $modulegroup_name => $modules) {
            if (is_array($modules)) {
                if (!sizeof($modules)) {
                    continue;
                }
                // get links
                $link_found = false;
                foreach ($modules as $module) {
                    $l = $module->getMenuLink($path);
                    if (sizeof($l)) {
                        $link_found = true;
                        break;
                    }
                }
                if (!$link_found) {
                    continue;
                }

                $menu = [];

                $menu["label"] = \App::T($modulegroup_name);
                $menu["link"] = "#";
                $menu["icon"] = $group_icon[$modulegroup_name]?$group_icon[$modulegroup_name]:"fa fa-link";

                if ($current_module->group == $modulegroup_name) {
                    $menu["active"] = true;
                }

                $menu["submenu"] = [];

                foreach ($modules as $module) {
                    $links = $module->getMenuLink($path);
                    if (!sizeof($links)) {
                        continue;
                    }

                    $submenu = [];
                    $submenu["label"] = $module->translate($module->name);
                    $submenu["icon"] = $module->icon();
                    if ($current_module->name == $module->name) {
                        $submenu["active"] = true;
                    }

                    if (sizeof($links) > 1) {
                        $submenu["submenu"] = $links;
                    } else {
                        $submenu["link"] = $links[0]["link"];
                    }

                    $menu["submenu"][] = $submenu;
                }
            } else {
                $module = $modules;
                $links = $module->getMenuLink($route);
                if (!sizeof($links)) {
                    continue;
                }
                $menu = [];
                $menu["label"] = $module->translate($module->name);
                $menu["icon"] = $module->icon;

                if ($current_module->name == $module->name) {
                    $menu["active"] = true;
                }

                if (sizeof($links) > 1) {
                    $menu["submenu"] = $links;
                } else {
                    $menu["link"] = $links[0]["link"];
                }
            }

            $sidebar_menu[] = $menu;
        }
        $data["sidebar_menu"] = $sidebar_menu;

        $system="composer/vendor/hostlink/r-alt";
        $data["script"][] = "$system/js/cookie.js";
        $data["script"][] = "$system/js/jquery.storageapi.min.js";


        if (file_exists(getcwd()."/system/{$version}/plugins/RT/locale/".$data["lang"].".js")) {
            $data["script"][] = "system/{$version}/plugins/RT/locale/".$data["lang"].".js";
        }

        $data["script"][] = "$system/plugins/RT/rt.js";
        $data["script"][] = "$system/js/tabajax.js";
        $data["script"][] = "$system/js/confirm.js";

        $data["css"][] = "$system/plugins/RT/css/rt.css";

        $data["alerts"] = [];
        foreach (\App\System::FlushMessage() as $msg) {
            $m = [];
            $m["message"] = $msg[0];
            $m["type"] = $msg[1];
            switch ($msg[1]) {
                case "success":
                    $m["icon"] = "fa-check";
                    break;
                case "warning":
                    $m["icon"] = "fa-exclamation-triangle";
                    break;
                case "danger":
                    $m["icon"] = "fa-exclamation-circle";
                    break;
            }
            $data["alerts"][] = $m;
        }

        $data["languages"] = \App\System::Language();

        $data["favs"] = [];
        // my fav
        $ds=\App\UI::find(["user_id=" . \App::UserID(), "uri='fav'"]);
        $ds=$ds->usort(function ($a, $b) {
            if ($a->content()["sequence"]>$b->content()["sequence"]) {
                return 1;
            } elseif ($a->content()["sequence"]<$b->content()["sequence"]) {
                return -1;
            }
            return 0;
        });
        foreach ($ds as $ui) {
            $content = json_decode($ui->layout, true);
            $data["favs"][] = $content;
        }

        $stream=new Stream($this->_template->render($data));


        return $response->withBody($stream);
    }

    public function __construct($route)
    {
        $this->route = $route;
        $user = \App::User();
        $setting = json_decode($user->setting, true);

        if ($setting["layout"] == "top-nav") {
            $template_file = \App::Path("AdminLTE/top-nav.html");
        } else {
            $template_file = \App::Path("AdminLTE/index.html");
        }

        $template_dir = dirname($template_file);
        //\Twig_Autoloader::register();
        $this->_twig["loader"] = new \Twig_Loader_Filesystem($template_dir);
        $this->_twig["environment"] = new \Twig_Environment($this->_twig["loader"]);
        $this->_twig["environment"]->addExtension(new \Twig_Extensions_Extension_I18n());

        if ($config = \App::Config("user", "roxy_fileman_path")) {
            $_SESSION["roxy_fileman_path"] = str_replace("{username}", \App::User()->username, (string)$config);
        }

        $translate_res = [];
        foreach (parse_ini_file(\App::Path("translate.ini"), true) as $k => $v) {
            $translate_res[$k] = $v;
        }
        // translate function
        $function = new \Twig_SimpleFunction('_', function ($a) use ($translate_res) {
                $lang = \App::User()->language;
            if ($text = $translate_res[$lang][$a]) {
                return $text;
            }

                return $a;
        }
            );
        $this->_twig["environment"]->addFunction($function);

        $this->_template = $this->_twig["environment"]->loadTemplate(basename($template_file));
    }
    public function template()
    {
        return $this->_template;
    }
}
