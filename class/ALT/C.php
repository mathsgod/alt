<?php

namespace ALT;
class C extends \My\C {
    public function format(callable $callback) {
        $this->attr('format', func_get_args());
    	return $this;
    }

}