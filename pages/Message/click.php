<?php

class Message_click extends App\Page {
    public function get() {
        $obj = $this->object();
        $obj->status = 1;
        $obj->save();
        App::Redirect($obj->uri);
    }
}

?>