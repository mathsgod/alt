<?php

namespace ALT;
class ButtonGroup extends \BS\ButtonGroup {
	private $route;
	public function __construct($route) {
		parent::__construct();
		$this->route = $route;
	}

	public function addButton($label = null, $uri = null) {
  		$b=parent::addButton($label);
        $b->classList[]="btn-primary";
        if($uri){
            $b->href($uri);
        }
		return $b;

	}
}
