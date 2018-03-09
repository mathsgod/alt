<?php

namespace ALT;
class Button extends \My\HTML\Button {

	public function __toString() {
		if ($object = p($this)->data("object")) {
			foreach ($this->attributes as $k => $v) {
				if ($v instanceof \Closure) {
					$this->attributes[$k] = \My\Func::_($v)->call($object);
				}

			}
		}
		return parent::__toString();
	}


}
