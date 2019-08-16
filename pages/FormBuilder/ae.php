<?php
use App\UserGroup;

class FormBuilder_ae extends ALT\Page
{

    public function post()
    {

        $obj=App\FormBuilder::_($_POST["name"]);
        $obj->bind($_POST);
        $obj->save();
        
        return ["code"=>200];
    }

    public function get()
    {

        if(!$this->app->composer->hasPackage("kevinchappell/form-builder")){
            $this->alert->warning("please install kevinchappell/form-builder");
            return;
        }
        //kevinchappell/form-builder

        $e=$this->createE();
        $e->add("Name")->input("name")->required();

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
