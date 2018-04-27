<?php

// Created By: Raymond Chong
// Created Date: 3/1/2011
// Last Updated:
namespace My;
class TreeNode extends \P\HTMLElement {
	public $ul = null;
	public $label;
	public $options = [];

	public function __construct($tag = "li") {
		parent::__construct($tag);
		if ($tag == "li") {
			$this->label = new \P\Text();
			p($this)->append($this->label);
		}
	}

	public function Close() {
		$this->closed = true;
	}

	public function addFolder($text) {
		if (!$this->ul) {
			$this->ul = p("ul")->appendTo($this);
		}

		$node = new TreeNode();
		$node->label->data = $text;
		$node->icon("far fa-folder-o");

		$this->ul->append($node);

		return $node;
	}

	public function addFile($text) {
		if (!$this->ul) {
			$this->ul = p("ul")->appendTo($this);
		}

		$node = new TreeNode();
		$node->label->data = $text;
		$node->icon("far fa-file");
		$this->ul->append($node);

		return $node;
	}
	
	public function a(){
		if(!$this->a){
			
			$this->a=p("a");
			p($this)->wrapInner($this->a);		
		}
		return $this->a;
	}


	public function icon($icon) {
		$this->options["icon"] = $icon;
		$this->attributes["data-jstree"] = $this->options;
		return $this;
	}

	public function disabled($value = true) {
		$this->options["disabled"] = $value;
		$this->attributes["data-jstree"] = $this->options;
		return $this;
	}

	public function opened($value = true) {
		$this->options["opened"] = $value;
		$this->attributes["data-jstree"] = $this->options;
		return $this;
	}

	public function selected($value = true) {
		$this->options["selected"] = $value;
		$this->attributes["data-jstree"] = $this->options;
		return $this;

	}


}
