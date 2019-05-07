<?php
class System_example_createV extends ALT\Page
{
    public function get()
    {
        $v = $this->createV($this->app->user);
        $v->add("Username", "username");

        $this->write($v);
    }
}
