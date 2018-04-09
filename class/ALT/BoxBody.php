<?php
namespace ALT;

class BoxBody extends \P\Query
{
    private $_page = null;

    public function __construct($page)
    {
        parent::__construct("alt-box-body");
        $this->_page = $page;
    }

    public function append($node)
    {
        if ($node instanceof \My\T) {
            $this->addClass("table-responsive");
            return parent::append((string)$node);
        } else {
            return parent::append($node);
        }
    }
}
