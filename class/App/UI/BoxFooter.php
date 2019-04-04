<?php

namespace App\UI;

use P\HTMLDivElement;


class BoxFooter extends HTMLDivElement
{

    public function __construct()
    {
        parent::__construct();
        $this->classList[] = "box-footer";
    }
}
