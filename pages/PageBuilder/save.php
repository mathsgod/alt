<?php

class PageBuilder_save extends App\Page
{

   


    public function post()
    {
        $obj=$this->object();;
        $obj->bind($_POST);
        $obj->content=json_encode($_POST["content"]);
        $html = [];
        foreach ($_POST["content"] as $widget) {
            $html[] = $this->renderWidget($widget);
        }

        $obj->save();


        return ["code" => 200, "html" => implode("", $html)];


    }
}