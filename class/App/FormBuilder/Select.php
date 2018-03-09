<?php

namespace App\FormBuilder;

use \P\HTMLOptionElement;
use \P\HTMLSelectElement;

class Select extends HTMLSelectElement
{
    public static function Create($params)
    {
        $select=new static();
        
        $select->classList->add($params["className"]);
        $select->setAttribute("name", $params["name"]);

        foreach ($params["values"] as $value) {
            $option=new HTMLOptionElement();
            $option->innerText=$value["label"];
            $option->setAttribute("value", $value["value"]);
            $select->add($option);
        }

        return $select;
    }
}
