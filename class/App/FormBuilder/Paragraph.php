<?php
namespace App\FormBuilder;

class Paragraph extends \P\HTMLElement
{
    public static function Create($params)
    {
        $ctrl=new static($params["subtype"]);
        $ctrl->classList->add($params["className"]);
        $ctrl->innerHTML=$params["label"];
        return $ctrl;
    }
}
