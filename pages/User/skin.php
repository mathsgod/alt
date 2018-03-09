<?php

class User_skin extends ALT\Page {
    public function get($name) {
    	$u=App::User();
    	$u->skin=$name;
    	$u->save();
    	App::Redirect();
    }
}

?>