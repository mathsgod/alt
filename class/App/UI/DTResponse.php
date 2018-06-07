<?
namespace App\UI;

use JsonSerializable;
use Exception;

class DTResponse implements JsonSerializable
{

    public $_columns = [];
    public $source = null;

    public function __construct($source)
    {
        $this->source = $source;
        $this->draw = $_GET["draw"];
        $this->columns = $_GET["columns"];
        $this->order = $_GET["order"];
        $this->start = $_GET["start"];
        $this->length = $_GET["length"];
        $this->search = $_GET["search"];

        foreach ($_GET["_columns"] as $i => $c) {
            $this->columns[$i] = array_merge($this->columns[$i], $c);
        }
    }

    public function where()
    {
        $where = [];
        return $where;
    }

    public function addEdit()
    {
        $c = new Column();
        $c->title = "";
        $c->type = "edit";
        $c->data = "__edit__";
        $c->name = "__edit__";
        $c->descriptor[] = function ($obj) {
            if (!$obj->canUpdate()) {
                return;
            }
            $a = html("a")->class("btn btn-xs btn-warning")->href($obj->uri("ae"));
            $a->i->class("fa fa-pencil-alt fa-fw");
            return $a;
        };
        $this->_columns["__edit__"] = $c;
        return $c;
    }

    public function addView()
    {
        $c = new Column();
        $c->title = "";
        $c->type = "view";
        $c->data = "__view__";
        $c->name = "__view__";
        $c->descriptor[] = function ($obj) {
            if (!$obj->canRead()) {
                return;
            }
            $a = html("a")->class("btn btn-xs btn-info")->href($obj->uri("v"));
            $a->i->class("fa fa-search fa-fw");
            return $a;
        };
        $this->_columns["__view__"] = $c;
        return $c;
    }


    public function addDel()
    {
        $c = new Column();
        $c->title = "";
        $c->type = "del";
        $c->data = "__del__";
        $c->name = "__del__";
        $c->descriptor[] = function ($obj) {
            if (!$obj->canDelete()) {
                return;
            }
            $a = html("a")->class("btn btn-xs btn-danger confirm")->href($obj->uri("del"));
            $a->i->class("fa fa-times fa-fw");
            return $a;
        };
        $this->_columns["__view__"] = $c;
        return $c;
    }

    public function add($name, $getter)
    {
        $c = new Column();
        $c->name = $name;
        $c->descriptor[] = $getter;

        $this->_columns[$name] = $c;

        return $c;
    }

    public function data()
    {
        $source = clone $this->source;

        foreach ($this->order as $o) {
            $c = $this->columns[$o["column"]];
            if ($c["orderable"] == "false") continue;
            $source->orderBy($c["data"] . " " . $o["dir"]);
        }

        foreach ($this->columns as $k => $c) {
            if ($c["searchable"] == "false") continue;
            if ($value = $c["search"]["value"]) {
                if ($c["searchType"] == "text") {
                    $w = [];
                    $w[] = [$c["data"] . " like ?", "%$value%"];
                    $source->where($w);
                } elseif ($c["searchType"] == "select") {
                    $w = [];
                    $w[] = [$c["data"] . " = ?", $value];
                    $source->where($w);
                } elseif ($c["searchType"] == "date"){
                    $value=json_decode($value,true);
                    $field=$c["data"];
                    $w=[];
                    $w[]=["date(`$field`) between ? and ?",[$value["from"],$value["to"]]];


                    $source->where($w);
                }
            }
        }

        $source->limit($this->start . "," . $this->length);

        $data = [];
        foreach ($source as $obj) {
            $d = [];
            foreach ($this->columns as $k => $c) {
                try {
                    if (array_key_exists($c["name"], $this->_columns)) {
                        $col = $this->_columns[$c["name"]];
                        $d[$c["data"]] = (string)$col->getData($obj, $k);
                    } else {
                        $d[$c["data"]] = null;
                    }
                } catch (Exception $e) {
                    $d[$c["data"]] = $e->getMessage();
                }
            }
            $data[] = $d;
        }

        return $data;
    }

    public function recordsTotal()
    {
        return $this->source->count();
    }

    public function recordsFiltered()
    {
        $source = clone $this->source;
        foreach ($this->columns as $k => $c) {
            if ($value = $c["search"]["value"]) {
                if ($c["searchType"] == "text") {
                    $w = [];
                    $w[] = [$c["data"] . " like ?", "%$value%"];
                    $source->where($w);
                } elseif ($c["searchType"] == "select") {
                    $w = [];
                    $w[] = [$c["data"] . " = ?", $value];
                    $source->where($w);
                }
            }
        }

        return $source->count();
    }

    public function jsonSerialize()
    {
        return [
            "draw" => $this->draw,
            "data" => $this->data(),
            "recordsTotal" => $this->recordsTotal(),
            "recordsFiltered" => $this->recordsFiltered()
        ];
    }
}