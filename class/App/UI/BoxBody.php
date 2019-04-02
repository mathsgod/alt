<?php

namespace App\UI;

class BoxBody extends Element
{

    public function __construct()
    {
        parent::__construct("div");

        $this->setAttribute("is", "alt-box-body");
        $this->classList[] = "box-body";
    }
}
