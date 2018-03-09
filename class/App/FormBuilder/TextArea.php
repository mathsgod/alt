<?php
namespace App\FormBuilder;

use P\HTMLTextAreaElement;

class TextArea extends Control
{
    
    public static function Create($params)
    {
        $ctrl=new HTMLTextAreaElement();
        $ctrl->classList->add($params["className"]);
        $ctrl->setAttribute("name", $params["name"]);

        
        if ($params["required"]) {
            $ctrl->setAttribute("required", true);
        }

        if ($params["placeholder"]) {
            $ctrl->setAttribute("placeholder", $params["placeholder"]);
        }

        if ($params["maxlength"]) {
            $ctrl->setAttribute("maxlength", $params["maxlength"]);
        }

        if ($params["value"]) {
            $ctrl->value=$params["value"];
        }

        if ($params["rows"]) {
            $ctrl->setAttribute("rows", $params["rows"]);
        }
        
        return $ctrl;
    }
}
