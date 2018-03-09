<?php
namespace ALT;
class Nav extends \BS\Nav {
    public function __construct() {
        parent::__construct();
        $this->addClass("nav-pills nav-stacked");
    }
}