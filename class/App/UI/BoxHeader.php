<?php

namespace App\UI;

class BoxHeader extends \P\HTMLDivElement
{
    protected $page;
    public $title;
    public $tools;

    public function __construct($page)
    {
        parent::__construct();
        $this->page = $page;
        $this->attributes["is"] = "alt-box-header";
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

    public function __toString()
    {
        if ($this->title) {
            $this->prepend($this->title);
        }

        return parent::__toString();
    }


}