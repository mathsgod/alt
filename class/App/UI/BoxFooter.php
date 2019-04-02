<?php

namespace App\UI;

class BoxFooter extends Element
{

    public function __construct()
    {
        parent::__construct("div");
        $this->classList[] = "box-footer";
    }
}

