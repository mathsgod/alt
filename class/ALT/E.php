<?php

namespace ALT;

use P\HTMLDivElement;

class E extends HTMLDivElement
{
    public $object;
    public $row = [];
    public $column_ratio = [2, 10];
    public $content;
    public $contents = [];

    public function __construct($object)
    {
        parent::__construct();
        $this->setAttribute("is", "alt-e");
        $this->classList[] = "form-horizontal";
        $this->object = $object;
        $this->content = p("div")->addClass("col-md-12")->appendTo($this);
        $this->contents[] = $this->content;
    }

    public function setColumnRatio($ratio)
    {
        $this->column_ratio = $ratio;
        return $this;
    }

    public function addSplit()
    {
        $this->content = p("div")->addClass("col-md-12")->appendTo($this);
        $this->contents[] = $this->content;

        //resize
        $col = floor(12 / count($this->contents));
        foreach ($this->contents as $content) {
            $content->removeClass();
            $content->addClass("col-md-{$col}");
        }
    }

    public function addBreak()
    {
        $div = p("div")->addClass("clearfix");
        foreach ($this->contents as $content) {
            $div->append($content);
        }
        $div->appendTo($this);

        $this->contents = [];
        $this->content = p("div")->addClass("col-md-12")->appendTo($this);
        $this->contents[] = $this->content;
    }

    public function addHr()
    {
        $hr = p("hr")->appendTo($this->content);
        return $hr;
    }

    public function addHeader($type)
    {
        $h = new C($type);
        p($h)->appendTo($this->content);
        return $h;
    }

    public function addParagraph()
    {
        $form_group = new C("div");
        $form_group->classList->add('form-group');
        p($form_group)->appendTo($this->content);
        return $form_group;
    }


    public function add($label, $getter = null)
    {
        $form_group = new \App\UI\FormGroup();
        p($form_group)->append("<label class='col-sm-2 control-label'>$label</label>");

        $c2 = new \App\UI\Col("div");
        //$form_group->setAttribute("is","bs-form-group");
        $c2->classList->add('col-sm-10');


        $cell = p("div");
        $cell->appendTo($c2);
        $cell->data("object", $this->object);

        $c2->cell[] = $cell[0];

        if ($getter) {
            $static = p("p");
            $static->addClass("form-control-static");
            if ($getter instanceof \Closure) {
                $static->html($getter($this->object));
            } else {
                $result = \My\Func::_($getter)->call($this->object);

                $static->text($result);
                if (is_object($result)) {
                    $cell->data("object", $result);
                }
            }

            $static->appendTo($cell);
        }

        $c2->callback = function ($object, $node) {
            if ($node->tagName == "a") {
                p($node)->wrap(p("p")->addClass("form-control-static"));
            }
        };

        $form_group->appendChild($c2);

        $this->content->append($form_group);
        $this->content->append("\n");



        return $c2;
    }
}
