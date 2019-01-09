<?php

namespace App\UI;

use App\Page;
use P\HTMLTableElement;
use ALT\C2;
use My\Func;

class Table extends HTMLTableElement
{
    protected $page;
    protected $objects;
    protected $columns = [];
    public $key;

    public function __construct($objects, Page $page)
    {
        parent::__construct();
        $this->objects = $objects;
        $this->page = $page;
        $this->attributes["is"] = "alt-table";
        $this->classList->add("table");

        $this->createTBody();
    }

    public function add($label, $getter)
    {
        $column = new C2("th");
        $column->default = $this->default;

        $this->columns[] = $column;

        if ($this->attributes["form-create"]) {
            $column->createTemplate = true;
        }

        $thead = $this->createTHead();
        $th = p($column)->appendTo($thead);

        $th->text($this->page->translate($label));
        $column->cell = new \P\Query;
        $i = 0;

        $tbody = $this->tBodies[0];

        foreach ($this->objects as $k => $obj) {
            if ($tbody->rows->length <= $i) {
                $row = $tbody->insertRow();
                if($obj instanceof \App\Model){
                    $row->attributes["data-index"]=$obj->id();
                }else{
                    if($this->key){
                        if(is_array($obj)){
                            $row->attributes["data-index"]=$obj[$this->key];    
                        }else{
                            $row->attributes["data-index"]=$obj->{$this->key};    
                        }
                        
                    }else{
                        $row->attributes["data-index"]=$k;
                    }
                }
                
            } else {
                $row = $tbody->rows[$i];
            }
            $i++;

            $cell = $row->insertCell();

            p($cell)->data("object", $obj);

            $column->cell[] = $cell;

            if ($getter) {
                if ($getter instanceof \Closure) {
                    p($cell)->html(call_user_func_array($getter, [$obj, $k]));
                } else {
                    p($cell)->text(Func::_($getter)->call($obj));
                }
            }
        }

        $form_name = $this->attr("form-name");

        $column->callback = function ($object, $node) use ($form_name) {
            $field = $node->attributes["data-field"];


            $tr = p($node)->closest("tr");

            $id = $tr->attr("data-index");

            $fn = "_u";
            if ($form_name)
                $fn = $form_name . "[u]";

            if ($node->attributes["multiple"]) {
                $node->attributes["name"] = "{$fn}[$id][$field][]";
            } else {
                $node->attributes["name"] = "{$fn}[$id][$field]";
            }
        };


        return $column;
    }

    public function row()
    {
        return p($this->rows);
    }

    public function addView()
    {
        $column = $this->add();
        $column->width(20);
        $as = $column->a()->addClass("btn btn-xs btn-info")->removeClass("btn-default")->html("<i class='fa fa-fw fa-search'></i>");
        foreach ($as as $a) {
            if ($object = p($a)->parent()->data("object")) {
                p($a)->attr('href', $object->uri('v'));
            }
        }
        return $as;
    }

    public function addDel()
    {
        $column = $this->add();
        $column->width(20);
        $as = $column->a()->addClass("btn btn-xs btn-danger confirm")->removeClass("btn-default")->html("<i class='fa fa-fw fa-times'></i>");
        foreach ($as as $a) {
            if ($object = p($a)->parent()->data("object")) {
                p($a)->attr('href', $object->uri('del'));
            }
        }
        return $as;
    }
    public function addEdit()
    {
        $column = $this->add();
        $column->width(20);
        $as = $column->a()->addClass("btn btn-xs btn-warning")->removeClass("btn-default")->html("<i class='fa fa-fw fa-pencil-alt'></i>");

        foreach ($as as $a) {
            if ($object = p($a)->parent()->data("object")) {
                p($a)->attr('href', $object->uri('ae'));
            }
        }
        return $as;
    }

    public function addCheckBox($index, $getter)
    {
        $column = $this->add(null, function ($obj) use ($index, $getter) {
            $input = p("input");
            $input->attr("type", "checkbox");
            $input->attr("is", "iCheck");
            if ($index) {
                $input->attr("index", $index);
                if (is_array($obj)) {
                    $input->val($obj[$index]);
                } else {
                    $input->val($obj->$index);
                }
                $input->attr("name", "{$index}[]");

                if($getter){
                    if($getter($obj)){
                        $input->attr("checked",true);
                    }


                }
            }
            return $input;
        })->width(20);


        /*$column->html("<input type='checkbox' class='iCheck' onClick='
var checked=$(this).is(\":checked\");
var index=$(this).closest(\"th\").index();
var td=$(this).closest(\"table\").find(\"tbody tr\").find(\"td:nth(\"+index+\")\");
if(checked){
	td.find(\".iCheck\").iCheck(\"check\");
}else{
	td.find(\".iCheck\").iCheck(\"uncheck\");
}

'/>");*/
        return $column;
    }


    public function addChildRow($label, $getter)
    {
        $label = $this->page ? $this->page->translate($label) : $label;
        return parent::addChildRow($label, $getter);
    }

    public function __toString()
    {
        foreach ($this->columns as $column) {
			if ($column->c_tpl->count()) {
				$column->attributes["c-tpl"] = (string )$column->c_tpl;
			}
        }
        
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
