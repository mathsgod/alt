<?php
class User_test extends R\Page
{
    public function get()
    {
        $w[] = ["status=?", 0];
        outp(App\User::Find($w));
        return;
        $this->redirect("");
        return;
        //outp((string)App::Config("user","domain"));
        outp(App\Config::_("domain"));
        return ["data" => __FILE__];
    }
}