<?php

namespace My\HTML;

class Form extends \P\Element {
	public function __construct()
	{
		parent::__construct("form");
	}
	public function addHidden($name, $value) {
		$input = p("input")->appendTo($this);
		$input->attr("name", $name)->attr("type", "hidden")->val($value);
		return $input;
	}
}
