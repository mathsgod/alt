<?php

namespace ALT;
class BoxHeader extends \P\Element {
	private $route = null;
	private $_title = null;

	private $tools = null;

	public function __construct($route) {
		parent::__construct("alt-box-header");
		$this->classList->add("with-border");
		$this->route = $route;
	}
	
	public function append($content){
		p($this)->append($content);
		return $this;
	}

	public function setTitle($title) {
		if($title){
			$this->attributes["title"]=$title;
		}
		return $this;
	}

	public function icon($icon){
		$this->attributes["icon"]=$icon;
		return $this;
	}

	public function addButton($label, $uri) {
        if($this->route){
            $label = $this->route->module()->translate($label);
        }
		$button = new Button();
		$button->addClass("btn-xs");
		$button->text($label);
		$button->href($uri);
		$this->append($button);
		return $button;
	}

	public function tools() {
		if ($this->_title == "") {
			$this->setTitle("");
		}
		if (!$this->tools) {
			$this->tools = new BoxHeaderTools($this->route);
			
			p($this)->append($this->tools);
		}
		return $this->tools;
	}
}