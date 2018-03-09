<?php

class System_root extends ALT\Page {
    public function post() {
        $cwd = CMS_ROOT;
        exec ("find $cwd/system -type d -exec chmod 0777 {} +");
        App::Msg("System rooted!!!");
        App::Redirect("System/root");
    }

    public function get() {
        $this->write("no need to root");
        return;
        $this->write(My::Form("Root the system?"));
    }
}

?>