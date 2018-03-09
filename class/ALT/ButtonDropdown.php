<?php

namespace ALT;
class ButtonDropdown extends \BS\ButtonDropdown {
	private $route;

	public function __construct($route, $label) {
		parent::__construct($label);
		$this->route = $route;
	}


	/*    public function addButton($label = null, $uri = null) {
	$btn = new Button();
	$btn->addClass("dropdown-toggle btn-primary btn-sm");
	$btn->attr("data-toggle", "dropdown");
	$btn->text($this->route->translate($label));
	$btn->append(' <span class="caret"></span>');

	$this->append($btn);

	if ($uri)$btn->href($uri);
	return $btn;
	}*/

	/*public function menu() {
	if (!$this->menu) {
	$this->menu = new DropdownMenu($this->route);
	$this->append($this->menu);
	}
	return $this->menu;
	}

	public function addItem($label, $href) {
	$menu = $this->menu();
	return $menu->addItem($label, $href);
	}

	public function addDivider() {
	return $this->menu()->addDivider();
	}*/
}
