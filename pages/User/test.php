<?php
class User_test extends ALT\Page{
    public function get(){
        //outp((string)App::Config("user","domain"));
        outp(App\Config::_("domain"));
        return ["data"=>__FILE__];
    }
}