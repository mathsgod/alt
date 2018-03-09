<?php

namespace ALT;
class BoxHeader extends \P\HTMLDivElement {
	private $route = null;
	private $_title = null;

	private $tools = null;

	public function __construct($route) {
		parent::__construct();
		$this->classList->add("box-header");
		$this->classList->add("with-border");
		$this->route = $route;
	}
	
	public function append($content){
		p($this)->append($content);
		return $this;
	}

	public function setTitle($title) {
		if (!$this->_title) {
			$this->_title = p("h3")->addClass('box-title');
			$this->_title->appendTo($this);
		}
		$this->_title->text($title);
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