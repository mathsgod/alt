<?php

namespace ALT;

class Navbar extends \App\UI\Element
{
    protected $page;
    public function __construct(Page $page)
    {
        $this->page = $page;
        parent::__construct("nav");
        $this->classList->add("navbar");
        $this->classList->add("navbar-default");


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
        $btn = new Button("primary", "sm", $uri);
        $this->_content->append($btn);
        p($btn)->addClass("navbar-btn");


        if ($label) {
            $label = $this->page->translate($label);
            p($btn)->text($label);
        }
        return $btn;
    }

    public function addButtonGroup()
    {
        $bg = new ButtonGroup($this->page);
        $this->_content->append($bg);
        return $bg;
    }

    public function addDropdown($label)
    {
        $dd = new \BS\Dropdown($label);
        $this->_content->append($dd);
        return $dd;
    }

    public function addButtonDropdown($label)
    {
        $bdd = new ButtonDropdown($this->page, $label);
        $bdd->button->classList->add("navbar-btn");
        $bdd->button->classList->add("btn-primary");
        $bdd->button->classList->add("btn-sm");


        $this->_content->append($bdd);
        return $bdd;
        $bdd->addClass("navbar-btn");
        $btn = $bdd->Button();
        $btn->addClass("btn-primary btn-sm")->text($this->page->translate($label));
        $btn->append(" <span class='caret'></span>");
        $this->_content->append($bdd);
        return $bdd;
    }

    public function addLayoutReset()
    {
        $btn = new Button("primary","sm","UI/reset_layout?uri=" . $this->page->path());
        $this->_content->append($btn);
        p($btn)->addClass("navbar-btn");
        p($btn)->text("Layout reset");
        $btn->icon("fa fa-fw fa-sync");
        return $btn;
    }

    public function __toString()
    {

        $this->_content->find(".btn-group")->find(".btn")->each(function ($i, $o) {
            $o->classList[] = "btn-sm";
            $o->classList[] = "navbar-btn";
        });

        return parent::__toString();
    }
}
