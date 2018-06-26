<?
namespace App\UI;

use \Box\Spout\Writer\WriterFactory;
use \Box\Spout\Common\Type;

use JsonSerializable;
use Exception;

class RTResponse implements JsonSerializable
{
    public $fields = [];
    public $source = null;

    public $_columns = [];
    public $page = 1;
    public $length;
    public $key;

    public function __construct()
    {
        $this->draw = $_GET["draw"];
        $this->request["columns"] = $_GET["columns"];
        $this->order = $_GET["order"];
        $this->page = $_GET["page"];
        $this->length = $_GET["length"];
        $this->search = $_GET["search"];
    }



    public function key($key)
    {
        $this->key = $key;
        return $this;
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
        $c->type = "raw";
        $c->data = "__edit__";
        $c->name = "__edit__";
        $c->className[] = "text-center";
        $c->width = "1px";
        $c->raw = true;
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
        $c->type = "raw";
        $c->data = "__view__";
        $c->name = "__view__";
        $c->className[] = "text-center";
        $c->width = "1px";
        $c->raw = true;
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
        $c->type = "delete";
        $c->data = "__del__";
        $c->name = "__del__";
        $c->width = "1px";
        $c->className[] = "text-center";
        $c->descriptor[] = function ($obj) {
            if (!$obj->canDelete()) {
                return;
            }
            return $obj->uri("del");
            $a = html("a")->class("btn btn-xs btn-danger confirm")->href($obj->uri("del"));
            $a->i->class("fa fa-times fa-fw");
            return $a;
        };
        $this->_columns["__del__"] = $c;
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
        foreach ($this->fields as $c) {
            $this->add($c, $c);
        }

        $source = $this->filteredSource();
        if ($this->page) {
            $source->limit([$this->page, $this->length]);
        }
        $data = [];
        foreach ($source as $obj) {
            $d = [];
            foreach ($this->request["columns"] as $k => $c) {
                try {

                    if (array_key_exists($c["data"], $this->_columns)) {
                        $col = $this->_columns[$c["data"]];

                        if ($col->type != "text") {
                            $d[$c["data"]] = ["type" => $col->type, "content" => (string)$col->getData($obj, $k)];
                        } elseif ($col->raw) {
                            $d[$c["data"]] = ["type" => "raw", "content" => (string)$col->getData($obj, $k)];
                        } else {
                            $v = $col->getData($obj, $k);

                            if (is_array($col->getData($obj, $k))) {
                                $d[$c["data"]] = $v;
                            } else {
                                $d[$c["data"]] = (string)$v;
                            }

                        }

                    } else {
                        $d[$c["data"]] = null;
                    }
                } catch (Exception $e) {
                    $d[$c["data"]] = $e->getMessage();
                }
            }

            if ($this->key) {
                $key = $this->key;
                $d["_key"] = $obj->$key;
            }

            $data[] = $d;
        }




        return $data;
    }

    public function recordsTotal()
    {
        return $this->source->count();
    }

    public function filteredSource()
    {
        $source = clone $this->source;

        foreach ($this->order as $o) {
            $source->orderBy($o["data"] . " " . $o["dir"]);
        }

        foreach ($this->request["columns"] as $k => $c) {
            $column = $this->_columns[$c["data"]];
            $value = $c["search"]["value"];

            if ($value !== null && $value !== "") {

                if ($column->searchCallback) {
                    $w = [];
                    $w[] = call_user_func($column->searchCallback, $value);
                    $source->where($w);
                    continue;
                }

                if ($c["searchMethod"] == "multiple") {
                    $field = $c["data"];
                    $s = [];
                    foreach ($value as $k) {
                        $s[] = "?";
                    }
                    $w = [];
                    $w[] = ["$field in (" . implode(",", $s) . ")", $value];
                    $source->where($w);
                    continue;
                } elseif ($c["searchMethod"] == "like") {
                    $w = [];
                    $w[] = [$c["data"] . " like ?", "%$value%"];
                    $source->where($w);
                } elseif ($c["searchMethod"] == "equal") {
                    $w = [];
                    $w[] = [$c["data"] . " = ?", $value];
                    $source->where($w);
                } elseif ($c["searchMethod"] == "date") {
                    $field = $c["data"];
                    $w = [];
                    $w[] = ["date(`$field`) between ? and ?", [$value["from"], $value["to"]]];
                    $source->where($w);
                }
            }
        }

        return $source;
    }

    public function recordsFiltered()
    {
        $source = $this->filteredSource();
        return $source->count();
    }

    public function jsonSerialize()
    {
        if ($_GET["type"]) {
            $this->exportFile($_GET["type"]);
            exit();
            return null;
        }

        return [
            "draw" => $this->draw,
            "data" => $this->data(),
            "total" => $this->recordsFiltered()
        ];
    }

    public function exportFile($type)
    {

        switch ($type) {
            case "xlsx":
                $t = Type::XLSX;
                break;
            case "csv":
                $t = Type::CSV;
                break;
        }
        $writer = WriterFactory::create($t);
        $writer->openToFile("php://output");

        $data = $this->data();

        foreach ($this->request["columns"] as $k => $c) {

            $col = $this->_columns[$c["data"]];

            if ($col->type != "text") continue;

            $row[] = $c["data"];
            $cols[] = $c["data"];
        }


        $writer->addRow($row);


        foreach ($data as $d) {
            $ds = [];
            foreach ($cols as $c) {
                if (is_array($d[$c])) {
                    $ds[$c] = $d[$c]["content"];
                } else {
                    $ds[$c] = $d[$c];
                }

            }
            $writer->addRow($ds);
        }

        $writer->close();
    }
}