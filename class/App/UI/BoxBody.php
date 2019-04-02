<?php

namespace App\UI;

use P\HTMLDivElement;


class BoxBody extends HTMLDivElement
{

    public function __construct()
    {
        parent::__construct();

        $this->setAttribute("is", "alt-box-body");
        $this->classList[] = "box-body";
    }
}
