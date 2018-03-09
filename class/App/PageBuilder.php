<?php
namespace App;

class PageBuilder extends \App\Model
{
    public static function _($path)
    {
        $w[] = ["path=?", $path];
        return PageBuilder::First($w);
    }

    public static function RenderWidget($w, $language)
    {
        if ($language && $w["language"]) {
            if ($w["language"] != $language) {
                return "";
            }
        }

        if ($w["type"] == "tab") {
            $h = "";
            foreach ($w["children"] as $child) {
                $h .= self::RenderWidget($child, $language);
            }
            return $h;
        }

        if ($w["type"] == "css") {
            $o = html("style");
            $o->html($w["value"]);
            return (string)$o;
        }

        if ($w["type"] == "text") {
            return htmlspecialchars($w["value"]);
        }

        if ($w["type"] == "javascript") {
            $o = html("script");
            $o->html($w["value"]);

            return (string)$o;
        }

        if ($w["type"] == "grid") {
            $div = html("div");
            $div->class("row");
            $h = "";
            foreach ($w["children"] as $child) {
                $h .= self::RenderWidget($child, $language);
            }
            $div->html($h);
            return (string)$div;
        }

        if ($w["type"] == "html") {
            return $w["value"];
        }

        if ($w["type"] == "div") {
            $div = html("div");
            if ($w["attributes"]) {
                $div->attr($w["attributes"]);
            }

            if ($w["size"]) {
                $div->class("col-md-" . $w["size"]);
            }

            $h = "";
            foreach ($w["children"] as $child) {
                $h .= self::RenderWidget($child, $language);
            }
            $div->html($h);

            return (string)$div;
        }

        if ($w["type"] == "image") {
            $img = html("img");
            $img->attr($w["attribute"]);
            return (string)$img;
        }

        if ($w["children"]) {
            $h = "";
            foreach ($w["children"] as $child) {
                $h .= self::RenderWidget($child, $language);
            }
            return $h;
        }

    }

    public function render($language)
    {
        $content = json_decode($this->content, true);
        $html = [];
        foreach ($content as $widget) {
            $html[] = self::RenderWidget($widget, $language);
        }
        return implode("\r\n", $html);
    }

    

    /*
    public function __invoke($request, $response)
    {
        $content=json_decode($this->content, true);
        foreach ($content as $component) {
            if ($component["type"]=="html") {
                $response->getBody()->write($component["value"]);
            }
        }

        $page=new \ALT\Page();
        $request=$request->withMethod("get");
        $page->get=function(){
            return ["code"=>200];
        };
        return $page->__invoke($request,$response);
    }*/
}
