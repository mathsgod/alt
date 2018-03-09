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

	public function icon($class) {
		$i = p($this)->find("i");
		if ($i->count() > 0) {
			$icon = $i[0];
			p($icon)->addClass($class);
		} else {
			$i = p("<i></i>")->addClass($class);
			p($this)->prepend($i);
			$i->after(new \P\Text(" "));
		}

		return $this;
	}


}
