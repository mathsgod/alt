<?php

namespace ALT;
class Grid extends \P\Query {
	private static $NUM = 0;
	private $item = 0;
	public $layout;
	private $_location = [];
	public function __construct() {
		parent::__construct("div");
		$this->addClass("grid");
		$this->attr("grid-num", self::$NUM);
        self::$NUM++;
	}

	private $_row = [];
	public function addRow() {
		$row = p("div");
		$row->addClass('row');
		$this->append($row);
		$this->_row[] = $row;
		return $row;
	}

	public function __toString() {
		foreach($this->_location as $row => $rows) {
			$r = $this->_row[$row];
			foreach($rows as $section => $sections) {
				$s = $r->find("section")[$section];
				// sorting
				ksort($sections);
				foreach($sections as $item => $items) {
					p($s)->append((string)$items);
				}
			}
		}
		return parent::__toString();
	}

	public function add($box, $location) {
		if ($box instanceof Box || $box instanceof Tab) {
			$box->pinable();
			$box->collapsible();
			$box->attributes["grid-item"]=$this->item;
		}


		if ($this->layout) {
			foreach($this->layout as $row => $sections) {
				foreach($sections as $section => $items) {
					foreach($items as $order => $item) {
						if ($item == $this->item) {

							if($this->_location[$row][$section][intval($order)]){
								$this->_location[$row][$section][] = $box;
							}else{
								$this->_location[$row][$section][intval($order)] = $box;
							}
							$this->item++;
							return;
						}
					}
				}
			}
		}

		$this->_location[$location[0]][$location[1]][] = $box;

	}
}

?>