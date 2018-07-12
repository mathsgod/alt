<?php

namespace App\UI;

class BoxBody extends \P\HTMLDivElement
{

    public function __construct(){
        parent::__construct();
        $this->attributes["is"] = "alt-box-body";
        $this->classList[]="box-body";
    }

}