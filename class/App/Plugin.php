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
            require_once($p->path . "/" . $php);
        }

        return $p;
    }

    public function __construct($name)
    {
       
        $cms_root = getcwd();
        $system = "composer/vendor/hostlink/r-alt";
        $system_root = System::Root();

        $composer_root = dirname(dirname(dirname(__DIR__)));
        $server=System::$request->getServerParams();
        $document_root=$server["DOCUMENT_ROOT"];

        $composer_base=substr($composer_root,strlen($document_root));
        $composer_base=str_replace(DIRECTORY_SEPARATOR,"/",$composer_base);

        $this->name = $name;
        $path = [];

        $path[] = [$composer_root."/".$name, "$composer_base/$name"];
        $path[] = [$composer_root."/vendor/$name", "$composer_base/vendor/$name"];
        
        $path[] = [$cms_root . "/composer/{$name}", "composer/$name"];
        $path[] = [$cms_root . "/composer/vendor/{$name}", "composer/vendor/$name"];
        $path[] = [$cms_root . "/plugins/{$name}", "plugins/$name"];

        $path[] = [$system_root . "/plugins/{$name}", $system . "/plugins/$name"];
        $path[] = [$system_root . "/plugins/{$name}.*", $system . "/plugins", "version"];
        $path[] = [$system_root . "/AdminLTE/plugins/{$name}", $system . "/AdminLTE/plugins/$name"];

        if (System::Config("user", "development")) {
            $ini_file = $cms_root . "/" . $system . "/plugins.development.ini";
        } else {
            $ini_file = $cms_root . "/" . $system . "/plugins.ini";
        }

        $found = false;
        foreach ($path as $p) {
            $r = glob($p[0], GLOB_ONLYDIR);
            rsort($r);

            if ($f = $r[0]) {

                $this->path = $f;
                $this->base = $p[1];

                if($p[2]=="version"){
                    $this->base.="/".basename($f);
                }
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
        //outp($this);


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
