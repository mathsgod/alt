<?php

namespace ALT;
class BoxHeaderTools extends \P\HTMLDivElement {
	public $route;
	public function __construct($route) {
		parent::__construct();
		$this->route = $route;
		$this->classList->add("box-tools");
		$this->classList->add("pull-right");
	}

	public function addButton() {
		$btn = new \BS\Button();
		$btn->classList->remove("btn-default");
		$btn->classList->add("btn-box-tool");
		p($btn)->appendTo($this);
		return $btn;
	}

	public function addLabel($text) {
		$label = new \BS\Label("primary");
		p($label)->appendTo($this);
		p($label)->text($text);
		return $label;
	}

	public function addButtonDropdown($label) {
		$bg = new ButtonDropdown($this->route);
		$bg->button()->classList->add('btn-box-tool');
		$bg->button()->classList->remove("btn-default");

		p($bg->button())->text($label);

		p($this)->append($bg);
		return $bg;
	}
}
