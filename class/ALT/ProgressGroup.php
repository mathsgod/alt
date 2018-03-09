<?php
namespace ALT;
class ProgressGroup extends \P\Query {
    private $progress_text;
    private $progress_number;

    private $progress_bar;

    public function __construct() {
        parent::__construct("div");
        $this->addClass("progress-group");

        $this->progress_text = p("span")->addClass('progress-test')->appendTo($this);
        $this->progress_number = p("span")->addClass('progress-number')->appendTo($this);

        $this->progress_bar = new \BS\ProgressBar();
        $this->progress_bar->classList[] = "sm";
        p($this->progress_bar)->appendTo($this);
    }

    public function setText($text) {
        $this->progress_text->text($text);
        return $this;
    }

    public function setMax($max) {
        $this->max = $max;
        $this->progress_bar->setValue($this->val / $this->max * 100);

        $this->progress_number->text("{$this->val}/{$this->max}");

        return $this;
    }

    public function setValue($val) {
        $this->val = $val;
        if ($this->max) {
            $this->progress_bar->setValue($this->val / $this->max * 100);
            $this->progress_number->text("{$this->val}/{$this->max}");
        }
        return $this;
    }
}

?>