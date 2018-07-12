<?php

namespace App\UI;

class BoxFooter extends \P\HTMLDivElement
{

    public function __construct()
    {
        parent::__construct();
        $this->classList[] = "box-footer";
    }

}