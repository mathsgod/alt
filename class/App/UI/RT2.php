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
    public $selectable = false;

    public $_page = null;
    public $buttons = [];
    public $exports = [];
    public $order = [];

    public function __construct($objects, $page)
    {
        parent::__construct("div");
        $this->attributes["is"] = "alt-rt2";
        $this->response = new RTResponse();

        $this->_page = $page;
    }

    public function order($name, $dir)
    {
        $this->order[] = ["name" => $name, "dir" => $dir];
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
        $c->noHide();
        $this->columns[] = $c;
        return $c;
    }

    public function addView()
    {
        $c = $this->response->addView();
        $c->noHide();
        $this->columns[] = $c;
        return $c;
    }

    public function addDel()
    {
        $c = $this->response->addDel();
        $c->noHide();
        $this->columns[] = $c;
        return $c;
    }

    public function addCheckbox($field){
        $c = new Column();
        $c->type="checkbox";
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
        $this->attributes[":selectable"] = $this->selectable ? "true" : "false";
        $this->attributes[":buttons"] = $this->buttons;
        $this->attributes[":exports"] = $this->exports;
        $this->attributes[":order"] = $this->order;
        return parent::__toString();
    }
}
