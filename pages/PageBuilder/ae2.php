<?php
use App\UserGroup;

class PageBuilder_ae2 extends ALT\Page
{

    public function post()
    {

        $obj=App\PageBuilder::_($_POST["path"]);
        if (!$obj) {
            $obj=new App\PageBuilder();
            $obj->path=$_POST["path"];
        }
        $obj->bind($_POST);
        $obj->save();
        
        return ["code"=>200];
    }

    public function get()
    {
        $e=$this->createE();
        $e->add("Path")->input("path")->required();

        $roles=[];
        foreach (UserGroup::find() as $ug) {
            $code=$ug->code?$ug->code:$ug->name;
            $roles[$code]=$ug->name;
        }

        return ["form1"=>$e,
            "formData"=>$this->object()->content,
            "roles"=>json_encode($roles)];
    }
}
