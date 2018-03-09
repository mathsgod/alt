<?php
namespace ALT;

class InputSelect extends \P\HTMLElement
{
    public function __construct()
    {
        parent::__construct("bs-input-select");
    }

    public function ds($a)
    {
        $this->setAttribute(":options", $a);
        return $this;
    }

    public function options($a)
    {
        $this->setAttribute(":options", $a);
        return $this;
    }
}
