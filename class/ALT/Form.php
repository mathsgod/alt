<?php

namespace ALT;

class Form extends \My\HTML\Form
{
    public $show_back = true;
    public $show_reset = false;
    private $submit_button;
    private $back_button;
    private $reset_button;
    private $_body = [];
    private $page;
    private $box;

    public function __construct($page = null)
    {
        parent::__construct();
        $this->page = $page;
        $this->attributes["method"] = "post";
  
        $this->box = new Box();
        $this->box->classList->add("box-primary");
        p($this)->append($this->box);

        $this->submit_button = new Button("success");
        $this->submit_button->icon("fa fa-check")->label(\App::T("Submit"));
        $this->submit_button->attributes["type"] = "submit";

        $this->box->footer()->append($this->submit_button);

        if (!$_SERVER["HTTP_X_FANCYBOX"]) {
            $this->reset_button = new Button("info");
            $this->reset_button->icon("fa fa-rotate-left")->label(\App::T("Reset"));
            $this->reset_button->attributes["type"] = "reset";
            $this->box->footer()->append($this->reset_button);

            $this->back_button = new Button("warning");
            $this->back_button->label(\App::T("Back"));
            $this->back_button->attributes["type"] = "button";
            if ($_GET["fancybox"]) {
                $this->back_button->attributes["data-fancybox-close"]=true;
            } else {
                $this->back_button->attributes["onClick"] = 'javascript:history.back(-1)';
            }
            
            $this->box->footer()->append($this->back_button);
        }
    }

    public function __toString()
    {
        if (!$_SERVER["HTTP_X_FANCYBOX"]) {
            if (!$this->show_back) {
                $this->back_button->classList->add("hide");
            }

            if (!$this->show_reset) {
                $this->reset_button->classList->add("hide");
            }
        }
        return parent::__toString();
    }

    public function action($action)
    {
        $this->attributes["action"] = $action;
        return $this;
    }

    public function addBody($body)
    {

        $this->box->body()->append((string )$body);
        return $this;
    }

    public function submitCheck($func)
    {
        $this->submit_check = $func;
        return $this;
    }

    public function box()
    {
        return $this->box;
    }
}
