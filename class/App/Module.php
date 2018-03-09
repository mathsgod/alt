<?php
namespace App;

class Module extends Model
{
    private static $_modules = [];
    public $group;
    public $icon = "fa fa-link";
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
            return "fa fa-link";
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

        foreach (glob(CMS_ROOT . "/pages/*", GLOB_ONLYDIR) as $m) {
            $b = basename($m);
            if (is_readable($m . "/" . $b . ".class.php")) {
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
        $ps=explode("/", $path);
        $file=System::Loader()->findFile($path);
        if ($file) {
            //find setting.ini
            $p=explode("/", dirname($file));
            $ps=explode("/", dirname($path));
            while (count($ps)) {
                if (file_exists($file = implode("/", $p)."/setting.ini")) {
                    $m=new Module;
                    $m->name=implode("/", $ps);
                    $m->class=implode("/", $ps);
                    foreach (parse_ini_file($file, true) as $k => $v) {
                        $m->sequence=PHP_INT_MAX;
                        $m->$k=$v;
                    }
                    
                    break;
                }
                array_pop($p);
                array_pop($ps);
            }
        }
        if (!$m) {
            $p=explode("/",$path);
            $m=self::_($p[0]);
        }

        return $m;
    }

    public static function _($name)
    {
        // read ini first
        $m = new Module;
        $m->sequence=PHP_INT_MAX;
        $m->class = $name;
        $m->name = $name;
        // read system ini
        if (file_exists($path = SYSTEM . "/pages/$name/setting.ini")) {
            foreach (parse_ini_file($path, true) as $k => $v) {
                $m->$k = $v;
            }
        }
        // read use ini
        if (file_exists($path = getcwd() . "/pages/$name/setting.ini")) {
            foreach (parse_ini_file($path, true) as $k => $v) {
                $m->$k = $v;
            }
        }
        // load from db
        if ($module = Module::first([["name=?",[$m->class]]])) {
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
        $name = $this->name;
        if (file_exists(CMS_ROOT . "/" . SYSTEM . "/pages/" . $name)) {
            foreach (glob(CMS_ROOT . "/" . SYSTEM . "/pages/" . $name . "/*.php") as $p) {
                $pi = pathinfo($p);
                $action[] = $pi;
            }
        }
        if (file_exists(CMS_ROOT . "/pages/" . $name)) {
            foreach (glob(CMS_ROOT . "/pages/" . $name . "/*.php") as $p) {
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
                $links[] = ["label" => $this->translate($k), "link" => $v["link"] , "icon" => $v["icon"] , "active" => ($path == $v["link"])];
            } else {
                if (!ACL::Allow($v)) {
                    continue;
                }
                $links[] = ["label" => $this->translate($k), "link" => $v , "icon" => "fa fa-link" , "active" => ($path == $v)];
            }
        }

        if ($this->showCreate()) {
            if (ACL::Allow($this->class, "C")) {
                $links[] = ["label" => $this->translate("Add"), "link" => $this->name . "/ae", "icon" => "fa fa-plus", "active" => ($path == $this->name . "/ae")];
            }
        }

        if ($this->show_list || $this->show_index) {
            if (ACL::Allow($this->name)) {
                $links[] = ["label" => $this->translate("List"), "link" => $this->name, "icon" => "fa fa-list", "active" => ($path == $this->name)];
            }
        }

        return $links;
    }

    public function translate($text)
    {
        $t = Translate::ByModule($this->name, \My::Language());
        return $t[$text]?$t[$text]:$text;
    }
}
