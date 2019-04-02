<?php

namespace My\HTML;
class Button extends \BS\Button {
	public function label($label) {
		$text_node = [];
		foreach ($this->childNodes as $node) {
			if ($node instanceof \P\Text) {
				$text_node[] = $node;
			}
		}
		
		foreach ($text_node as $n) {
			$this->removeChild($n);
		}

		p($this)->append(new \P\Text(" " . $label));

		return $this;
	}

	public function addClass($class){
		p($this)->addClass($class);
		return $this;
	}


}
