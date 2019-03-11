<?php
class User_test extends ALT\Page
{
    public $_a = [1, 2];

    public function get()
    {

        $f="_a";
        $s=$this->$f;

        outp($s);
        
        return;

        echo property_exists(User_test, "_a");
    }
}
