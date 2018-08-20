<?php

namespace App\UI;

use App\Page;

class BoxClassTokenList extends \P\DOMTokenList
{
    public function offsetSet($offset, $value)
    {
        if (in_array($value, Box::BOX_CLASS)) {
            $this->token = array_diff($this->token, Box::BOX_CLASS);
        }
        parent::offsetSet($offset, $value);
    }
}

class Box extends \P\HTMLDivElement
{
    const BOX_CLASS = ["box-default", "box-primary", "box-success", "box-info", "box-warning", "box-danger"];

    protected $page = null;
    public $collapsible = false;
    public $collapsed = false;
    public $pinable = false;
    public $dataUrl = null;

    public function __construct(Page $page)
    {
        parent::__construct();
        $this->page = $page;
        $this->classList = new BoxClassTokenList;

        $this->attributes["is"] = "alt-box";
        $this->classList->add("box");
        $this->classList->add("box-primary");
    }

    public function collapsible($collapsible)
    {
        $this->collapsible = $collapsible;
        return $this;
    }

    public function pinable($pinable)
    {
        $this->pinable = $pinable;
        return $this;
    }

    public function __get($name)
    {
        if ($name == "header") {
            $this->header = new BoxHeader($this->page);
            return $this->header;
        }

        if ($name == "body") {
            $this->body = new BoxBody($this->page);
            return $this->body;
        }

        if ($name == "footer") {
            $this->footer = new BoxFooter($this->page);
            return $this->footer;
        }

        return parent::__get($name);
    }

    public function body()
    {
        return p($this->body);
    }

    public function header($title = null)
    {
        if ($title) {
            $this->header->title = $title;
        }
        return p($this->header);
    }

    public function __toString()
    {
        if ($this->dataUrl) {
            $body = $this->body;
            $this->attributes["data-url"] = $this->dataUrl;
        }


        $v = get_object_vars($this);

        if ($v["header"]) {
            $this->appendChild($v["header"]);
        }

        if ($v["body"]) {
            $this->appendChild($v["body"]);
        }

        if ($v["footer"]) {
            $this->appendChild($v["footer"]);
        }

        if ($this->collapsible) {
            $this->attributes[":collapsible"] = "true";
        }

        if ($this->collapsed) {
            $this->attributes[":collapsed"] = "true";
        }

        if ($this->pinable) {
            $this->attributes[":pinable"] = "true";
        }

        if ($this->draggable) {
            $this->attributes[":draggable"] = "true";
        }


        return parent::__toString();
    }


}