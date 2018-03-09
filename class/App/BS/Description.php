<?php

namespace App\BS;
class Description extends \BS\Description {
    protected $_route;
    public function __construct($route) {
        $this->_route = $route;
        parent::__construct();
    }

    public function add($title, $description) {
        if (!$this->_route) {
            return parent::add(App::T($title), $description);
        } else {
            return parent::add($this->_route->translate($title), $description);
        }
    }
}
