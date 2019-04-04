<?php

use P\Document;
use App\UI\Box;

class User_test extends ALT\Page
{
    public $_a = [1, 2];

    public function get()
    {

        $box = $this->createBox();

        $box->header("test");
        $box->pinable(true);

        $this->write($box);

        return;

        throw new Exception("test");

        return;
        //print_r($doc->nodes);
        die();

        die();

        $f = "_a";
        $s = $this->$f;

        outp($s);

        return;

        echo property_exists(User_test, "_a");
    }
}
