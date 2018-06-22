<?php
namespace App;

use Symfony\Component\Yaml\Yaml;

class Module extends Model
{
    private static $_modules = [];
    public $group;
    public $icon = "fa fa-fw fa-link";
    public $class;
    public $menu = [];
    public $sequence = PHP_INT_MAX;

    public $show_index = true;
    public $show_list = false;
    public $show_create = false;
    public $show_update = true;
    public $show_delete = true;

    public $log = true;

    public $hide = false;

    public function icon()
    {
        if (!$this->icon) {
            return "fa fa-fw fa-link";
        }
        return $this->icon;
    }

    public function __toString()
    {
        return $this->class;
    }

    public static function All()
    {
        if (sizeof(self::$_modules)) {
            return self::$_modules;
        }


        $page = \App::Config("system", "pages");
        if (!$page) {
            $page = "pages";
        }

        foreach (glob(CMS_ROOT . DIRECTORY_SEPARATOR . $page . DIRECTORY_SEPARATOR . "*", GLOB_ONLYDIR) as $m) {
            $b = basename($m);

            if (is_readable($m . "/" . $b . ".class.php")) {
                self::$_modules[$b] = Module::_($b);
                continue;
            } elseif (is_readable($m . "/setting.yml")) {
                self::$_modules[$b] = Module::_($b);
                continue;
            } elseif (is_readable($m . "/setting.ini")) {
                self::$_modules[$b] = Module::_($b);
                continue;
            }
        }

        foreach (glob(SYSTEM . "/pages/*", GLOB_ONLYDIR) as $m) {
            $b = basename($m);
            self::$_modules[$b] = Module::_($b);
        }

        return self::$_modules;
    }

    public static function ByPath($path)
    {
        $ps = explode("/", $path);
        $ps=array_values(array_filter($ps,"strlen"));
        $path=implode("/",$ps);
    
        $file = App::_()->loader->findFile($path);
        if ($file) {
            //find setting.ini
            $p = explode("/", dirname($file));
            $ps = explode("/", dirname($path));
            while (count($ps)) {
                if (file_exists($file = implode("/", $p) . "/setting.ini")) {
                    $m = new Module;
                    $m->name = implode("/", $ps);
                    $m->class = implode("/", $ps);
                    foreach (parse_ini_file($file, true) as $k => $v) {
                        $m->sequence = PHP_INT_MAX;
                        $m->$k = $v;
                    }

                    break;
                }
                array_pop($p);
                array_pop($ps);
            }
        }
        if (!$m) {
            $p = explode("/", $path);
            $p=array_values(array_filter($p,"strlen"));
            $m = self::_($p[0]);
        }

        return $m;
    }

    public static function _($name)
    {
        // read ini first
        $m = new Module;
        $m->sequence = PHP_INT_MAX;
        $m->class = $name;
        $m->name = $name;

        $app=App::_();
        $page = App::_()->config["system"]["page"];
        if (!$page) {
            $page = "pages";
        }

        // read system ini
        if (file_exists($path = SYSTEM . "/pages/$name/setting.ini")) {
            foreach (parse_ini_file($path, true) as $k => $v) {
                $m->$k = $v;
            }
        }

        // read config

        if($config=$app->config["module"][$name]){
            foreach($config as $k=>$v){
                $m->$k=$v;
            }
        }
        

        // read use ini
        if (file_exists($path = CMS_ROOT . "/$page/$name/setting.ini")) {
            foreach (parse_ini_file($path, true) as $k => $v) {
                $m->$k = $v;
            }
        }

        if (file_exists($path = SYSTEM . "/pages/$name/setting.yml")) {
            $config = Yaml::parseFile($path);
            foreach ($config as $k => $v) {
                $m->$k = $v;
            }
        }
        if (file_exists($path = CMS_ROOT . "/$page/$name/setting.yml")) {
            $config = Yaml::parseFile($path);
            foreach ($config as $k => $v) {
                $m->$k = $v;
            }
        }

        // load from db
        if ($module = Module::first([["name=?", [$m->class]]])) {
            foreach ($module as $k => $v) {
                if ($k == "menu") {
                    continue;
                }

                if ($v != "") {
                    $m->$k = $v;
                }
            }
        }

        return $m;
    }

    public function getAction()
    {

        $page = \App::Config("system", "pages");
        if (!$page) {
            $page = "pages";
        }

        $name = $this->name;
        if (file_exists(CMS_ROOT . "/" . SYSTEM . "/pages/" . $name)) {
            foreach (glob(CMS_ROOT . "/" . SYSTEM . "/pages/" . $name . "/*.php") as $p) {
                $pi = pathinfo($p);
                $action[] = $pi;
            }
        }
        if (file_exists(CMS_ROOT . "/$page/" . $name)) {
            foreach (glob(CMS_ROOT . "/$page/" . $name . "/*.php") as $p) {
                $pi = pathinfo($p);
                $action[] = $pi;
            }
        }

        return $action;
    }

    public function showCreate()
    {
        if (property_exists($this, "show_create")) {
            return $this->show_create;
        }
        return false;
    }

    public function getMenuLink($path)
    {
        if ($this->hide) {
            return [];
        }
        $links = [];
        foreach ($this->menu as $k => $v) {
            if (is_array($v)) {
                if (!ACL::Allow($v["link"])) {
                    continue;
                }
                $links[] = [
                    "label" => $this->translate($k),
                    "link" => $v["link"],
                    "icon" => $v["icon"],
                    "active" => ($path == $v["link"]),
                    "target" => $v["target"],
                    "keyword" => $this->translate($k)
                ];
            } else {
                if (!ACL::Allow($v)) {
                    continue;
                }
                $links[] = [
                    "label" => $this->translate($k),
                    "link" => $v,
                    "icon" => "fa fa-fw fa-link",
                    "active" => ($path == $v),
                    "keyword" => $this->translate($k)
                ];
            }
        }

        if ($this->showCreate()) {
            if (ACL::Allow($this->class, "C")) {
                $links[] = [
                    "label" => $this->translate("Add"),
                    "link" => $this->name . "/ae",
                    "icon" => "fa fa-fw fa-plus",
                    "active" => ($path == $this->name . "/ae"),
                    "keyword" => ""
                ];
            }
        }

        if ($this->show_list || $this->show_index) {
            if (ACL::Allow($this->name)) {
                $links[] = [
                    "label" => $this->translate("List"),
                    "link" => $this->name,
                    "icon" => "fa fa-fw fa-list",
                    "active" => ($path == $this->name),
                    "keyword" => ""
                ];
            }
        }

        return $links;
    }

    public function translate($text)
    {
        $t = Translate::ByModule($this->name, \My::Language());
        return $t[$text] ? $t[$text] : $text;
    }

    public function keyword()
    {
        return $this->name . " " . $this->translate($this->name);
    }
}
