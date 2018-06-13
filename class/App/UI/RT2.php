<?php
namespace App\UI;

//class RT extends \RT
class RT2 extends \P\HTMLElement
{
    public $columns = [];
    public $data = [];
    public $ajax = null;
    public $response = null;
    public $responsive = true;
    public $cellUrl = null;
    public $pageLength = 25;

    public function __construct()
    {
        parent::__construct("div");
        $this->attributes["is"] = "alt-rt2";


        $this->response = new RTResponse();
    }

    public function order($data, $dir)
    {
        $this->order = [$data, $dir];
        return $this;
    }

    public function add($title, $getter)
    {
        $c = new Column();

        if ($this->_page) {
            $c->title = $this->_page->translate($title);
        } else {
            $c->title = $title;
        }

        $c->descriptor[] = $getter;


        if ($getter instanceof Closure) {
            $c->data = md5(new \ReflectionFunction($this->getter));
            $c->name = $c->data;
        } else {
            $c->data = $getter;
            $c->name = $getter;
        }

        $c->data = str_replace(["(", ")"], "_", $c->data);


        $this->columns[] = $c;


        return $c;
    }


    public function addEdit()
    {
        $c = $this->response->addEdit();
        $this->columns[] = $c;
        return $c;
    }

    public function addView()
    {
        $c = $this->response->addView();
        $this->columns[] = $c;
        return $c;
    }

    public function addDel()
    {
        $c = $this->response->addDel();
        $this->columns[] = $c;
        return $c;
    }

    public function __toString()
    {

        $this->attributes[":columns"] = $this->columns;
        $this->attributes[":data"] = $this->data;
        $this->attributes[":ajax"] = $this->ajax;
        $this->attributes[":responsive"] = $this->responsive ? "true" : "false";

        $this->attributes["cell-url"] = $this->cellUrl;

        $this->attributes[":page-length"] = $this->pageLength;
        return parent::__toString();
    }
}
