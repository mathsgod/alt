<?php
namespace ALT;
class BoxFooter extends \P\Query {
    private $_route = null;
    public function __construct($route) {
        parent::__construct("div");
        $this->addClass("box-footer");
        $this->_route = $route;
    }

    public function button() {
        $button = new \BS\Button();
        $button->addClass("btn-sm");
        $this->append($button);
        return $button;
    }
}

?>