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

        $page = self::$_app->config["system"]["pages"];
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
        $ps = array_values(array_filter($ps, "strlen"));
        if ($module = self::All()[$ps[0]]) {
            return $module;
        }
        return new Module();
    }

    public static function _($name)
    {
        if (!$name) {
            return null;
        }
        // read ini first
        $m = new Module;
        $m->sequence = PHP_INT_MAX;
        $m->class = $name;
        $m->name = $name;

        $page = self::$_app->config["system"]["page"];
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

        if ($config = $app->config["module"][$name]) {
            foreach ($config as $k => $v) {
                $m->$k = $v;
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
        /*
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
        }*/

        return $m;
    }

    public function getAction()
    {
        $app = self::$_app;

        $page = $app->config["system"]["page"];
        if (!$page) {
            $page = "pages";
        }

        $pi = $app->pathInfo();

        $name = $this->name;
        if (file_exists($file = $pi["cms_root"] . "/pages/" . $name)) {
            foreach (glob($file . "/*.php") as $p) {
                $pi = pathinfo($p);
                $action[] = $pi;
            }
        }
        if (file_exists($file = $pi["system_root"] . "/pages/" . $name)) {
            foreach (glob($file . "/*.php") as $p) {
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
