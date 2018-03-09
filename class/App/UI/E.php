<?php
namespace App\UI;
class E extends \ALT\E {
	private $_page;
	public function __construct($object, $page) {
		$this->_page = $page;
		parent::__construct($object);
	}
	public function add($label, $getter = null) {
		return parent::add($this->_page->translate($label), $getter);
	}
}