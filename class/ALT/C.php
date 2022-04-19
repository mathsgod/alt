<?php

namespace ALT;

class C extends \My\C
{
    function format($callback, $params = null)
    {
        $this->attr('format', func_get_args());
        return $this;
    }
}
