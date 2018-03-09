<?php

class PageBuilder_index extends ALT\Page
{
    public function get()
    {
        $t=$this->createTab();
        $t->add("All Page", "list");
        $this->write($t);
    }
}
