<?php
use App\FormBuilder;

class FormBuilder_list extends ALT\Page
{
    public function get()
    {
        $rt=$this->createRT([$this,"ds"]);
        $rt->addEdit();
        $rt->addDel();
        $rt->add("Name", "name");
        $this->write($rt);
    }

    public function ds($rt)
    {
        $data["total"]=FormBuilder::Count();
        $data["data"]=FormBuilder::Find($w, $rt->order(), $rt->limit());
        return $data;
    }
}
