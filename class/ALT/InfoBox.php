<?php

namespace ALT;

class InfoBox extends \P\Query
{
    public function __construct()
    {
        parent::__construct("div");
        $this->addClass("info-box");
    }

    public $content = null;
    public function content()
    {
        if (!$this->content) {
            $this->content = p("div")->addClass('info-box-content')->appendTo($this);
        }
        return $this->content;
    }

    public function addText($text)
    {
        return p("span")->addClass('info-box-text')->text($text)->appendTo($this->content());
    }

    public function addNumber($number)
    {
        return p("span")->addClass('info-box-number')->text($number)->appendTo($this->content());
    }

    public function setIcon($icon, $bg_color)
    {
        $e = p("span")->addClass("info-box-icon")->addClass($bg_color);
        p("i")->addClass($icon)->appendTo($e);
        $e->appendTo($this);
        return $e;
    }

    public function addProgress()
    {
        $pb = new \BS\ProgressBar();
        p($pb)->appendTo($this->content());
        return $pb;
    }

    public function addProgressDescription($text)
    {
        return p("span")->addClass("progress-description")->text($text)->appendTo($this->content());
    }
}
