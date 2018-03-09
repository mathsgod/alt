<?php
namespace Twig;
class twig2pot extends \Twig_Extensions_Extension_I18n {
    public $token_parser;

    public function getTokenParsers() {
        $this->token_parser = new \Twig\TokenParser();


        return array($this->token_parser);
    }
}

?>