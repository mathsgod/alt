<?php
use App\PageBuilder;

class PageBuilder_list extends ALT\Page
{
    public function get()
    {
        $rt=$this->createRT([$this,"ds"]);
        $rt->addEdit();
        $rt->addDel();
        $rt->add("Name", "name");
        $rt->add("Path", "path");
        $this->write($rt);
    }

    public function ds($rt)
    {
        $data["total"]=PageBuilder::Count();
        $data["data"]=PageBuilder::Find($w, $rt->order(), $rt->limit());
        return $data;
    }
}
