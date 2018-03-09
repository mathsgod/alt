<?php
namespace ALT;
class DropdownMenu extends \BS\DropdownMenu {
    private $route;
    public function __construct($route) {
        parent::__construct();
        $this->route = $route;
    }

    public function addItem($item, $href) {
        return parent::addItem($this->route->translate($item), $href);
    }
}