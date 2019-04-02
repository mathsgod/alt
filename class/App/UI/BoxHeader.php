<?php

namespace App\UI;

class BoxHeader extends Element
{
    protected $page;
    public $tools;

    public function __construct($page)
    {
        parent::__construct("div");
        $this->page = $page;
        $this->setAttribute("is", "alt-box-header");
        $this->classList[] = "box-header";

        $this->tools = new BoxTools($page);
        $this->append($this->tools);
    }

    public function addButton($label, $uri)
    {
        $button = new Button($this->page);
        $button->classList[] = "btn-xs";
        $button->text($label);
        $button->href($uri);
        $this->appendChild($button);
        return $button;
    }

    public function __set($name, $value)
    {
        if($name=="title"){
            $this->prepend($value);
            return;
        }
        parent::__set($name, $value);
    }

    
}

