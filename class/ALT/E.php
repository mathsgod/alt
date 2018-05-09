<?php
namespace ALT;

class E extends \P\HTMLDivElement
{
    public $object;
    public $row = [];
    public $column_ratio = [2, 10];
    public $content;
    public $contents = [];

    public function __construct($object)
    {
        parent::__construct();
        $this->attributes["is"]="alt-e";
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


    public function add($label, $getter)
    {
        $form_group = new C2("bs-form-group");
        $form_group->setAttribute("label", $label);

        $this->content->append($form_group);
        $this->content->append("\n");

        $cell = p("div");
        $cell->appendTo($form_group);
        $cell->data("object", $this->object);

        $form_group->cell[] = $cell[0];

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

        $form_group->callback = function ($object, $node) {
            if ($node->tagName == "a") {
                p($node)->wrap(p("p")->addClass("form-control-static"));
            }
        };


        return $form_group;
    }
}