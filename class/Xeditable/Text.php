<?php
namespace Xeditable;
class Text extends \P\HTMLAnchorElement {
	public function __construct() {
		parent::__construct();
		$this->setAttribute("href", "javascript:void(0)");
		$this->setAttribute("xeditable", true);
		$this->setAttribute("data-mode", "inline");
		$this->setAttribute("data-type", "text");
	}

	public function __set($name, $value) {
		if($name=="placeholder"){
			$this->setAttribute("data-placeholder", $value);
			return;
		}elseif ($name == "emptyText") {
			$this->setAttribute("data-emptyText", $value);
			return;
		} elseif ($name == "title") {
			$this->setAttribute("data-title", $value);
			return;
		} elseif ($name == "pk") {
			$this->setAttribute("data-pk", $value);
			return;
		} elseif ($name == "name") {
			$this->setAttribute("data-name", $value);
			return;
		} elseif ($name == "url") {
			$this->setAttribute("data-url", $value);
			return;
		}
		parent::__set($name, $value);
	}
}

?>