<?php
class Dashboard_search extends App\Page
{
    public function get($q)
    {
        return ["q" => $q];
        $this->write("search");
    }
}