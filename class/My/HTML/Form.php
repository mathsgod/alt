<?php

namespace My\HTML;

use P\HTMLFormElement;
class Form extends HTMLFormElement {

	public function addHidden($name, $value) {
		$input = p("input")->appendTo($this);
		$input->attr("name", $name)->attr("type", "hidden")->val($value);
		return $input;
	}
}
