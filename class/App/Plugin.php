<?php

namespace App;

class Plugin
{
    public $name;
    public $path;
    public $setting;
    private static $plugins = [];
    public static function Load($name)
    {
        $p = new Plugin($name);
        self::$plugins[] = $name;

        foreach ($p->setting["php"] as $php) {
            require_once ($p->path . "/" . $php);
        }

        return $p;
    }

    public function __construct($name)
    {
        $cms_root=getcwd();
        $system="composer/vendor/hostlink/r-alt";

        $this->name = $name;
        $path = [];
        $path[] = [$cms_root . "/plugins/{$name}.*", "plugins"];
        $path[] = [$cms_root . "/" . $system . "/plugins/{$name}", $system . "/plugins"];
        $path[] = [$cms_root . "/" . $system . "/plugins/{$name}.*", $system . "/plugins"];
        $path[] = [$cms_root . "/" . $system . "/AdminLTE/plugins/{$name}", $system . "/AdminLTE/plugins"];
    	$path[] = [$cms_root . "/composer/{$name}", $system . "/composer"];
    	$path[] = [$cms_root . "/composer/vendor/{$name}", $system . "/composer/vendor"];
        $path[] = [$cms_root . "/" . $system . "/composer/vendor/{$name}", $system . "/composer/vendor"];

        if (System::Config("user", "development")) {
            $ini_file=$cms_root . "/" . $system . "/plugins.development.ini";
        } else {
            $ini_file=$cms_root . "/" . $system . "/plugins.ini";
        }

        $found = false;
        foreach ($path as $p) {
            $r = glob($p[0], GLOB_ONLYDIR);
            rsort($r);

            if ($f = $r[0]) {

                $this->path = $f;
                $this->base =  substr($this->path, strlen($cms_root)+1);
                // read ini
                $ini = parse_ini_file($ini_file, true);
                $this->setting = $ini[$name];

                $ini2 = parse_ini_file($cms_root . "/plugins.ini", true);
                if ($ini2[$name]) {
                    $this->setting = $ini2[$name];
                }

                $found = true;
                break;
            }
        }

        if (!$found) {
            throw new \Exception($name . " not found");
        }
    }

    public function csss()
    {
        $csss = [];
        foreach ($this->setting["css"] as $css_f) {
            if (file_exists($this->path . "/" . $css_f)) {
                $csss[] = $this->base . "/" . $css_f;
            }
        }

        return $csss;
    }

    public function jss($language)
    {
        $jss = [];
        foreach ($this->setting["js"] as $js) {
            $f = str_replace("{language}", $language, $js);

            if (file_exists($this->path . "/" . $f)) {
                $jss[] = $this->base . "/" . $f;
            }
        }
        return $jss;
    }
}
