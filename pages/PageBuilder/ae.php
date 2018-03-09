<?php
use App\UserGroup;

class PageBuilder_ae extends ALT\Page
{
    public function renderWidget($w)
    {
        if ($w["type"] == "div") {
            $div = html("div");
            if ($w["attributes"]) {
                $div->attr($w["attributes"]);
            }

            foreach ($w["children"] as $child) {
                $h .= $this->renderWidget($child);
            }
            return (string)$div;
        }

        if ($w["type"] == "tab") {

        }

        if ($w["type"] == "grid") {
            $div = html("div");
            $div->class("row");
            $h = "";
            foreach ($w["children"] as $child) {
                $h .= $this->renderWidget($child);
            }
            $div->html($h);
            return (string)$div;
        }


       if ($w["type"] == "text") {
           outp($w);
            return htmlspecialchars($w["value"]);
        }

        if ($w["type"] == "html") {
            return $w["value"];
        }

        if ($w["type"] == "container") {
            $div = html("div");
            if ($w["size"]) {
                $div->class("col-md-" . $w["size"]);
            }

            $h = "";
            foreach ($w["children"] as $child) {
                $h .= $this->renderWidget($child);
            }
            $div->html($h);

            return (string)$div;
        }

        if ($w["type"] == "image") {
            $img = html("img");
            if ($w["data"]["src"]) {
                $img->src($w["data"]["src"]);
            }
            if ($w["data"]["width"]) {
                $img->width($w["data"]["width"]);
            }
            if ($w["data"]["height"]) {
                $img->width($w["data"]["height"]);
            }

            return (string)$img;
        }

    }

    public function post()
    {
        $obj = $this->object();
        $obj->bind($_POST);
        $obj->content = json_encode($_POST["content"]);
        $html = [];
        foreach ($_POST["content"] as $widget) {
            $html[] = $this->renderWidget($widget);
        }

        $obj->save();


        return ["code" => 200];
    }

    public function data()
    {
        $obj = $this->object();

        return [
            "pagebuilder_id" => $obj->pagebuilder_id,
            "content" => json_decode($obj->content, true),
            "name" => $obj->name
        ];
    }

    public function get()
    {
        $this->addLib("ckeditor/ckeditor");
        $this->addLib("ace");
//        $this->addLib("Sortable");
        return ["object" => json_encode($this->object())];


    }
}
