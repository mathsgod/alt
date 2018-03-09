<?php
namespace App\UI;
class Button extends \BS\Button {
    private $_route;
    public function __construct($route) {
        $this->_route = $route;
        parent::__construct();
    }
    public function text($text) {
        return parent::text($this->_route->translate($text));
    }

    public function __toString() {
        if ($href = $this->attr("href")) {
            if (!\App\ACL::Allow($href)) {
                return "";
            }
        }

        return parent::__toString();
    }
}

?>