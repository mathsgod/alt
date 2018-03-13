<?php

class _404_not_found extends ALT\Page
{
    public function get($msg)
    {
        $this->header("404 Error Page");
    	//outp($this);
    }
}