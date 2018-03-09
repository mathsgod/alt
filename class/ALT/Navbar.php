<?php

namespace ALT;

class Navbar extends \P\Query
{
    protected $page;
    public function __construct($page)
    {
        $this->page = $page;
        parent::__construct("nav");
        $this->addClass("navbar navbar-default");

        $header = p("div")->addClass("navbar-header")->appendTo($this);
        $button = p("button")->attr("type", "button")->addClass("navbar-toggle collapsed")->appendTo($header);
        $button->attr("data-toggle", "collapse");
        $button->attr("data-target", "#_navbar_collapse");
        $button->append('<span class="sr-only">Toggle navigation</span>');
        $button->append('<span class="icon-bar"></span>');
        $button->append('<span class="icon-bar"></span>');
        $button->append('<span class="icon-bar"></span>');

        $this->_content = p("div")->appendTo($this);
        $this->_content->addClass("navbar-collapse collapse");
        $this->_content->attr("id", "_navbar_collapse");
        $this->_content->css("height", "1px");
    }

    public function addButton($label = null, $uri = null)
    {
        $btn = new Button();
        $this->_content->append($btn);
        p($btn)->addClass("navbar-btn btn-primary btn-sm");

        if ($label) {
            $label = $this->page->translate($label);
            p($btn)->text($label);
        }
        if ($uri) {
            $btn->href($uri);
        }
        return $btn;
    }

    public function addButtonGroup($label = null, $uri = null)
    {
        $bg = new ButtonGroup($this->page);
        $this->_content->append($bg);
        return $bg;
    }

    public function addButtonDropdown($label)
    {
        $bdd = new ButtonDropdown($this->page);
        $bdd->addClass("navbar-btn");
        $btn = $bdd->Button();
        $btn->addClass("btn-primary btn-sm")->text($label);
        $btn->append(" <span class='caret'></span>");
        $this->_content->append($bdd);
        return $bdd;
    }

    public function __toString()
    {

        $this->_content->find(".btn-group")->find(".btn")->each(function ($i, $o) {
            $o->classList[] = "btn-sm";
            $o->classList[] = "navbar-btn";
        }
        );

        return parent::__toString();
    }
}
