<?php

namespace App\UI;

use App\Page;

class BoxClassTokenList extends \P\DOMTokenList
{
    public function offsetSet($offset, $value)
    {
        $values = $this->values();
        if ($this->values()) {
            if (in_array($value, BOX::BOX_CLASS)) {
                $this->value = implode(" ", array_diff($values, BOX::BOX_CLASS));
            }
        }
        parent::offsetSet($offset, $value);
    }
}

class Box extends Element
{
    const BOX_CLASS = ["box-default", "box-primary", "box-success", "box-info", "box-warning", "box-danger"];

    protected $page = null;
    public $collapsible = false;
    public $collapsed = false;
    public $pinable = false;
    public $dataUrl = null;
    public $dataUri = null;
    private static $NUM = 0;

    public function __construct(Page $page)
    {
        parent::__construct("div");
        $this->page = $page;

        $this->setAttribute("is", "alt-box");
        $this->classList->add("box");

        $this->dataUri = $page->path() . "/box[" . self::$NUM . "]";

        $ui = \App\UI::_($this->dataUri);
        if ($ui->layout) {
            $layout = json_decode($ui->layout, true);
            if ($layout["collapsed"]) {
                $this->collapsed = $layout["collapsed"];
            }
        }

        self::$NUM++;
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
            $this->prependChild($this->header);
            return $this->header;
        }

        if ($name == "body") {
            $this->body = new BoxBody($this->page);
            $this->appendChild($this->body);
            return $this->body;
        }

        if ($name == "footer") {
            $this->footer = new BoxFooter($this->page);
            $this->appendChild($this->footer);
            return $this->footer;
        }

        switch ($name) {
            case "classList":
                if (!$this->hasAttribute("class")) {
                    $this->setAttribute("class", "");
                }
                return new BoxClassTokenList($this->attributes->getNamedItem("class"));
                break;
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
            $this->setAttribute("data-url", $this->dataUrl);
        }

        if ($this->dataUri) {
            $this->setAttribute("data-uri", $this->dataUri);
        }


        $v = get_object_vars($this);


        if ($this->collapsible) {
            $this->setAttribute(":collapsible", "true");
        }

        if ($this->collapsed) {
            $this->setAttribute(":collapsed", "true");
        }

        if ($this->pinable) {
            $this->setAttribute(":pinable", "true");
        }

        if ($this->draggable) {
            $this->setAttribute(":draggable", "true");
        }


        return parent::__toString();
    }
}
