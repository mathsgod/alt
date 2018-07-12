<?php

namespace App\UI;

use App\Page;

class Table extends \ALT\Table
{
    protected $page;

    public function __construct($objects, Page $page)
    {
        parent::__construct($objects);
        $this->page = $page;
        $this->addClass("table-condensed");
    }

    public function addCheckBox($index)
    {
        $column = $this->add(null, function ($obj) use ($index) {
            $input = p("input");
            $input->attr("type", "checkbox");
            $input->addClass("iCheck");
            if ($index) {
                $input->attr("index", $index);
                if (is_array($obj)) {
                    $input->val($obj[$index]);
                } else {
                    $input->val($obj->$index);
                }
                $input->attr("name", "{$index}[]");
            }
            return $input;
        })->width(20);


        $column->html("<input type='checkbox' class='iCheck' onClick='
var checked=$(this).is(\":checked\");
var index=$(this).closest(\"th\").index();
var td=$(this).closest(\"table\").find(\"tbody tr\").find(\"td:nth(\"+index+\")\");
if(checked){
	td.find(\".iCheck\").iCheck(\"check\");
}else{
	td.find(\".iCheck\").iCheck(\"uncheck\");
}

'/>");
        return $column;
    }

    public function addDel()
    {
        $column = $this->add();
        $column->width(20);
        $as = $column->a()->addClass("btn btn-xs btn-danger confirm")->removeClass("btn-default")->html("<i class='fa fa-times'></i>");
        foreach ($as as $a) {
            if ($object = p($a)->parent()->data("object")) {
                if ($object->canDelete()) {
                    p($a)->attr('href', $object->uri('del'));
                } else {
                    p($a)->remove();
                }
            }
        }
        return $as;
    }

    public function addEdit()
    {
        $column = $this->add();
        $column->width(20);
        $as = $column->a()->addClass("btn btn-xs btn-warning")->removeClass("btn-default")->html("<i class='fa fa-pencil-alt'></i>");

        foreach ($as as $a) {
            if ($object = p($a)->parent()->data("object")) {
                if ($object->canUpdate()) {
                    p($a)->attr('href', $object->uri('ae'));
                } else {
                    p($a)->remove();
                }
            }
        }
        return $as;
    }

    public function add($label, $getter)
    {
        $label = $this->page ? $this->page->translate($label) : $label;
        return parent::add($label, $getter);
    }

    public function addChildRow($label, $getter)
    {
        $label = $this->page ? $this->page->translate($label) : $label;
        return parent::addChildRow($label, $getter);
    }

    public function __toString()
    {
        $html = parent::__toString();
        $o = p($html);
        $o->find("td")->each(function ($i, $o) {
            if (p($o)->find("input,textarea,select")->count()) {
                p($o)->wrapInner("<div class='form-group no-margin'></div>");
            }
        });
        return (string )$o;
    }
}
