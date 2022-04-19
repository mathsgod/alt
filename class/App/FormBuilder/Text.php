<?php

namespace App\FormBuilder;

use P\HTMLCollection;
use P\HTMLInputElement;

class Text extends HTMLCollection
{
    public function bind($object)
    {
        if ($name = $this->getAttribute("name")) {
            if (is_object($object)) {
                $this->value = $object->$name;
            } else {
                $this->value = $object[$name];
            }
        }
        return $this;
    }

    public static function Create($params)
    {


        $text = new static();

        $text->classList->add($params["className"]);
        $text->setAttribute("name", $params["name"]);

        if ($params["subtype"] == "text") {
            $text->setAttribute("type", "text");
        }

        if ($params["maxlength"]) {
            $text->setAttribute("maxlength", $params["maxlength"]);
        }

        if ($params["minlength"]) {
            $text->setAttribute("minlength", $params["minlength"]);
        }

        if ($params["value"]) {
            $text->setAttribute("value", $params["value"]);
        }

        if ($params["required"]) {
            $text->setAttribute("required", true);
        }

        return $text;
    }
}
