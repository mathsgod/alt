<?php
namespace ALT;
class Skin {
    public $path;
    public $name;
    public $setting = [];
    public function __construct($skin_path) {
        $this->name = basename($skin_path);
        $this->path = $skin_path;

        if (file_exists($skin_path . "/setting.ini")) {
            $this->setting = parse_ini_file($skin_path . "/setting.ini");
        }

        $this->icon = "<img width='40' height='27' src='{$skin_path}/icon.png'>";
    }

    public function css() {
        $css = [];
        // load require
        if ($base = $this->setting["base"]) {
            $css[] = "system/" . \App::Version() . "/AdminLTE/dist/css/skins/{$base}.min.css";
        }

        foreach($this->setting["css"] as $c) {
            $css[] = $c;
        }

        return $css;
    }
}

?>