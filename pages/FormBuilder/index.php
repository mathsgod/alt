<?php

class FormBuilder_index extends ALT\Page
{
    public function get()
    {
        $t=$this->createTab();
        $t->add("All form", "list");
        $this->write($t);
    }
}
