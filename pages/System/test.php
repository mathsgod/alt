<?php

class System_test extends ALT\Page {
    public function get() {
        $locale = "zh_HK";

        putenv("LANG=" . $locale);
        setlocale(LC_ALL, $locale);

        $directory = App\System::$root . "/locale";
        bindtextdomain("app", $directory);

    	bind_textdomain_codeset("app","UTF-8");

    	textdomain("app");

        echo _("hello");
        return ["a" => 1];
    }
}

?>