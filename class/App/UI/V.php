<?php

namespace App\UI;

use \My\Func;
use App\Page;

class V extends Box
{
    public $columns = [];
    public $tableClass = [];
    public $column_ratio = [2, 10];
    public $tables = [];
    public $row = 0;

    public $container;

    public function __construct($object, Page $page)
    {
        parent::__construct($page);
        $this->object = $object;
        $this->tableClass = "table-condensed";
        $this->body->classList->add('no-padding');
        $this->classList->add("no-border");
        $this->container = $this->body();

        $this->addTable();
    }

    public function setColumnRatio($ratio)
    {
        $this->column_ratio = $ratio;
        return $this;
    }

    public function header($title = null)
    {
        $this->classList->remove("no-border");
        $this->classList->add('box-primary');
        return parent::header($title);
    }

    public function addBreak()
    {
        $this->row++;

        $row_div = p("div")->addClass("row")->appendTo($this->body());
        $wrap_div = p("div")->addClass("col-xs-12")->appendTo($row_div);
        $this->container = $wrap_div;

        $this->addTable();
        return $this;
    }

    public function addSplit()
    {
        $tables = $this->tables[$this->row];
        $c = count($tables);
        if ($c == 1 && count($this->tables) == 1) {
            $div = p("div")->addClass("row")->appendTo($this->container);
            $tables[0]->appendTo($div);
            $wrap_div = p("div")->addClass("col-xs-12");
            $tables[0]->wrap($wrap_div);
        }

        $row_div = $tables[0]->parent()->parent();

        $this->container = p("div")->addClass("col-xs-12")->appendTo($row_div);
        $this->addTable();

        $c = count($this->tables[$this->row]);
        $col_class = floor(12 / $c);

        foreach ($row_div->children("div") as $div) {
            p($div)->removeClass();
            p($div)->addClass("col-md-" . $col_class);
        }
        return $this->table;
    }

    public function addNext()
    {
        $this->table = p("table")->addClass("table")->appendTo($this->container);
        $this->table->addClass($this->tableClass);
        $this->table->append(p("tbody"));
    }

    public function addTable()
    {

        $this->table = p("table")->addClass("table")->appendTo($this->container);
        $this->table->addClass($this->tableClass);
        $this->table->append(p("tbody"));
        $this->tables[$this->row][] = $this->table;
        return $this->table;
    }

    public function addHr()
    {

        $tbody = $this->table->find('tbody');
        $tr = p("tr")->appendTo($tbody);
        $tr->append("<td colspan='2'><hr style='margin:0px'/></td>");
        return $this;
    }

    public function add($label, $getter = null)
    {
        $label = ($this->page) ? $this->page->translate($label) : $label;
        $tbody = $this->table->find("tbody");

        $tr = new Col("tr");
        p($tr)->appendTo($tbody);
        $th = p("th")->addClass("bg-primary")->append($label);
        $th->appendTo($tr);

        $col = $this->column_ratio[0];
        $th->addClass("col-xs-{$col} col-md-{$col} col-lg-{$col}");

        $cell = p("td");
        $cell->data("object", $this->object);
        $tr->cell[] = $cell[0];
        if ($getter instanceof \Closure) {
            $cell->html($getter($this->object));
        } elseif ($getter) {
            $cell->attr("data-field", $getter);
            $cell->text(Func::_($getter)->call($this->object));
        }

        $cell->appendTo($tr);

        return $tr;
    }

    public function __get($name)
    {
        if ($name == "header") {
            $this->classList->remove('no-border');
            $this->classList->add('box-primary');
        }
        return parent::__get($name);
    }
}
