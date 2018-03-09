<?php

class UI_clear extends App\Page {
    public function get() {
        App::Msg("Truncate UI");
        App::DB()->exec("truncate UI");
        App::Redirect();
    }
}