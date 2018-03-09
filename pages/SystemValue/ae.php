<?php
// Created By: Raymond Chong
use App\SystemValue;

class SystemValue_ae extends ALT\Page
{
    public function post()
    {
        foreach ($_POST["value"] as $lang => $val) {
            $obj = SystemValue::_($_POST["name"], $lang);
            if (!$obj) {
                $obj = new SystemValue();
                $obj->name = $_POST["name"];
                $obj->language = $lang;
            }
            $obj->value = $val;
            $obj->Save();
        }
        App::Redirect();
    }

    public function get()
    {
        $this->addLib("codemirror");

        $obj = $this->object();
        $mv = $this->createE();
        $mv->add("Name")->input("name")->val($obj->name)->required();

        foreach (App::Language() as $v => $l) {
            $mv->add("Value " . $l)->textarea()->text(SystemValue::_($obj->name, $v))->attr('name', "value[$v]")->addClass("code");
        }

        
        $this->write($this->createForm($mv)->action(''));
    }
}
