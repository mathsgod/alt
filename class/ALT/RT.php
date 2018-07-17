<?
namespace ALT;

use \Box\Spout\Writer\WriterFactory;
use \Box\Spout\Common\Type;

class RTRow
{
    public $style = null;

    public function style($func)
    {
        $this->style = $func;
    }
}

class RTColumn
{
    public $index;
    public $label;
    public $width;

    public $searchable = false;
    //public $searchType = "text";
    public $searchMultiple = false;
    public $sortable = false;
    public $visible = true;
    public $searchOptions = null;
    public $alink = null;
    public $descriptor = [];
    public $align = null;
    public $editable = false;

    public $edit_type;
    public $sort_index = null;

    public $max_width = null;
    public $min_width = null;

    public $wrap = false;
    public $cell = [];
    public $rt;
    public $overflow;
    public $css = [];
    public $fixed = false;

    public $resizable = true;

    public static $_Index = 0;

    public function __construct($rt)
    {
        $this->rt = $rt;
        $this->attr("page-size", 25);
    }

    public function fixed()
    {
        $this->fixed = true;
        return $this;
    }

    public function maxWidth($max_width)
    {
        $this->max_width = $max_width;
        return $this;
    }
    public function minWidth($min_width)
    {
        $this->min_width = $min_width;
        return $this;
    }
    public function editable($type = "text", $data = null)
    {
        $this->editable = true;
        $this->edit_type = $type;
        $this->edit_data = $data;
        
        return $this;
    }

    public function overflow($value)
    {
        if ($value == "hidden") {
            $this->wrap = false;
            $this->css["overflow"] = "hidden";
            $this->css["text-overflow"] = "ellipsis";
        }
        $this->overflow = $value;
        return $this;
    }

    public function ss($index)
    {
        if ($index) {
            $this->index($index);
        }
        $this->search();
        $this->sort();
        return $this;
    }

    public function search($callback)
    {
        $this->searchable = true;
        $this->search_type = 'text';
        $this->search_callback = $callback;
        return $this;
    }

    public function resizable($value = true)
    {
        $this->resizable = $value;
        return $this;

    }

    public function searchEQ()
    {
        $this->searchable = true;
        $this->search_type = 'equal';
        return $this;
    }

    public function sort($index)
    {
        $this->sortable = true;
        if ($index instanceof \Closure) {
            $this->sort_callback = $index;
        } else {
            $this->sort_index = $index;
        }

        return $this;
    }

    public function searchOption($objects, $display_member, $value_member)
    {
        $this->searchable = true;
        $this->searchOptions = array($objects, $display_member, $value_member);
        $this->search_type = 'select';
        return $this;
    }

    public function searchSelect2($objects, $display_member, $value_member)
    {
        $this->searchable = true;
        $this->searchOptions = array($objects, $display_member, $value_member);
        $this->search_type = 'select2';
        return $this;
    }

    public function searchMultiple($objects, $display_member, $value_member)
    {
        $this->searchable = true;
        $this->searchOptions = array($objects, $display_member, $value_member);
        $this->search_type = 'multiselect';
        $this->searchMultiple = true;
        return $this;
    }


    public function searchCallback($callback)
    {
        $this->searchable = true;
        $this->search_callback = $callback;
        return $this;
    }

    public function searchDate()
    {
        $this->searchable = true;
        $this->search_type = 'date';
        return $this;
    }

    public function searchDateRange()
    {
        $this->searchable = true;
        $this->search_type = 'daterange';
        return $this;
    }

    public function searchRange()
    {
        $this->searchable = true;
        $this->search_type = 'range';
        return $this;
    }

    public function index($index)
    {
        if ($index instanceof \Closure) {
            $index = md5(new \ReflectionFunction($index));
        }
        if (!$index) {
            $index = self::$_Index;
            self::$_Index++;
        }
        $this->index = $index;
        return $this;
    }

    public function alink($alink = null)
    {
        $this->alink = $alink;
        return $this;
    }

    public function width($width)
    {
        $this->width = $width;
        return $this;
    }

    public function visible($visible)
    {
        $this->visible = $visible;
        return $this;
    }

    public function render($render)
    {
        $this->format = $render;
        return $this;
    }

    public function format($format)
    {
        $this->format = $format;
        return $this;
    }

    public function className($className)
    {
        $this->className = $className;
        return $this;
    }

    public function gf($getter)
    {
        if ($func instanceof Closure) {
            $this->index = md5(new ReflectionFunction($getter));
        } else {
            $this->index = $getter;
        }
        $this->descriptor[] = $getter;
        return $this;
    }

    public function align($align)
    {
        $this->align = $align;
        return $this;
    }

    public function css($css)
    {
        $this->cell["css"] = $css;
        return $this;
    }


    public $childs = [];

    public function button()
    {
        $btn = new \ALT\Button();
        $btn->addClass('btn-xs');
        $this->childs[] = $btn;
        return $btn;
    }

    public function wrap()
    {
        $this->wrap = true;
        return $this;
    }

    public function nowrap()
    {
        $this->wrap = false;
        return $this;
    }

    public $attr = [];
    public function attr($name, $value)
    {
        $this->attr[$name] = $value;
        return $this;
    }

    public function getSearchValue()
    {
        $value = $_GET["search"][$this->index];
        if ($_GET["search_remember"] != "") {
            $search_remember = ($_GET["search_remember"] == "true");
        } else {
            //$d = json_decode($_COOKIE[$this->rt->uri()], true);
            //$search_remember = $d["search_remember"];
        }

        if ($this->search_type == "date" || $this->search_type=="range") {
            $data = [];
            if (isset($_GET["search"][$this->index]["from"])) {
                $data["from"] = $_GET["search"][$this->index]["from"];
                if ($search_remember) {
                    $_SESSION["app"]["rt"][$this->rt->uri()]["search"][$this->index]["from"] = $data["from"];
                }
            } else {
                $data["from"] = $_SESSION["app"]["rt"][$this->rt->uri()]["search"][$this->index]["from"];
            }

            if (isset($_GET["search"][$this->index]["to"])) {
                $data["to"] = $_GET["search"][$this->index]["to"];
                if ($search_remember) {
                    $_SESSION["app"]["rt"][$this->rt->uri()]["search"][$this->index]["to"] = $data["to"];
                }
            } else {
                $data["to"] = $_SESSION["app"]["rt"][$this->rt->uri()]["search"][$this->index]["to"];
            }
            return $data;
        }

        if (isset($_GET["search"][$this->index])) {
            $value = $_GET["search"][$this->index];
            if ($search_remember) {
                $_SESSION["app"]["rt"][$this->rt->uri()]["search"][$this->index] = $value;
            }
            return $value;
        }
        if ($search_remember) {
            return $_SESSION["app"]["rt"][$this->rt->uri()]["search"][$this->index];
        }
    }

    public function cellClass($callback)
    {
        $this->cell["class"] = $callback;
        return $this;
    }
}


class RTRequest
{
    public $rt;
    public $request;
    public function __construct($rt)
    {
        $this->rt = $rt;
        $this->request = $rt->request;
    }

    public function search()
    {
        $db = App::DB();
        $search = [];

        foreach ($this->rt->columns as $column) {
            $value = $column->getSearchValue();
            $name = $column->index;

            if ($value != "") {
                $search[$name] = substr($db->quote($value), 1, -1);
            }
        }
        return $search;
    }

    private function getColumn($index)
    {
        foreach ($this->rt->columns as $column) {
            if ($column->index == $index) {
                return $column;
            }
        }
    }

    public function order()
    {
        if($_GET["order"]){
            if($_GET["order"][0]["column"]==$this->rt->_attribute["sort-field"]){
                return $_GET["order"][0]["column"]." ".$_GET["order"][0]["dir"];
            }
        }
        

        //prevent user direct input order
        $columns_index = array_map(function ($column) {
            return $column->index;
        }, $this->rt->columns);


        $order = array();
        if ($_GET["order"]) {
            foreach ($_GET["order"] as $i => $column) {
                $col = $this->getColumn($column["column"]);
                $dir = $column["dir"];
                if ($dir != "asc" && $dir != "desc") {
                    $dir = "";
                }

                if ($col->sort_callback instanceof \Closure) {
                    $order["(" . call_user_func($col->sort_callback) . ")"] = $dir;
                } else {
                    if (in_array($column["column"], $columns_index)) {
                        $order[$column["column"]] =  $dir;
                    }
                }
            }
        } else {
            return null;
        }

        if (count($order) == 0) {
            return null;
        }
        return $order;
    }

    public function limit()
    {
        $query = $this->request->getQueryParams();
        if ($query["type"]) {
            return [1, PHP_INT_MAX];
        }


        $page = intval($_GET["page"]);
        if ($page <= 0) {
            $page = 1;
        }
        return [$page, intval($_GET["length"])];
    }

    public function where()
    {
        $where = [];
        foreach ($this->rt->columns as $column) {
            if (!$column->searchable) {
                continue;
            }

            $value = $column->getSearchValue();
            $name = $column->index;
            if ($column->search_callback) {
                if ($value != "") {
                    $where[] = call_user_func($column->search_callback, $value, $s);
                }
            } elseif ($column->search_type == 'range') {
                if ($value["from"] != "") {
                    $where[] = ["$name >= ?", $value["from"]];
                }
                if ($value["to"] != "") {
                    $where[] = ["$name <= ?", $value["to"]];
                }
                
            } elseif ($column->search_type == 'select') {
                if ($value != "") {
                    $where[] = ["$name = ?", $value];
                }
            } elseif ($column->search_type == "date") {
                if ($value["from"] != "") {
                    $where[] = ["$name >= ?", $value["from"]];
                }
                if ($value["to"] != "") {
                    $where[] = ["$name <= ?", $value["to"]];
                }
            } elseif ($column->search_type == "equal") {
                if ($value != "") {
                    $where[] = ["$name = ?", $value];
                }
            } else {
                if ($value != "") {
                    $where[] = ["$name like ?", "%{$value}%"];
                }
            }
        }
        return $where;
    }
}


class RT implements \JsonSerializable
{
    public $columns = [];
    public $_attribute = [];
    public $data_func;
    public $export_xlsx = false;
    public $export_csv = false;
    public $request;

    public function __construct($request)
    {
        $this->request = $request;
        $this->_attribute["source"] = $_SERVER["REQUEST_URI"];
        $this->_attribute["responsive"] = true;
        $this->row = new RTRow();
    }

    public function uri()
    {
        return $_SERVER["REQUEST_URI"];
    }

    public function order($index, $dir = "asc")
    {
        $this->_attribute["sort-field"] = $index;
        $this->_attribute["sort-dir"] = $dir;
        return $this;
    }

    public function key($key)
    {
        $this->key = $key;
        return $this;
    }

    public function add($label, $callback)
    {
        $column = new RTColumn($this);
        $column->type = 'text';
        $column->label = $label;
        $column->index($callback);
        if ($callback) {
            $column->descriptor[] = $callback;
        }
        $this->columns[] = $column;
        return $column;
    }

    public function addEdit()
    {
        $column = new RTColumn();
        $column->resizable = false;
        $column->descriptor[] = function ($obj) {
            $uri = $obj->uri('ae');
            if ($obj->canUpdate() && \App::ACL($uri)) {
                // return "<a href=\"{$uri}\">" . Icon::_("edit") . "</a>";
                return "<a href=\"{$uri}\" class='btn btn-xs btn-warning'><i class='fa fa-fw fa-pencil-alt'/></a>";
            }
        };
        $column->align("center");
        $column->index("[edit]");
        $column->width(28);

        $this->columns[] = $column;
        return $c;
    }

    public function addView()
    {
        $column = new RTColumn();
        $column->resizable = false;
        $column->descriptor[] = function ($obj) {
            $uri = $obj->uri('v');
            if ($obj->canRead() && \App::ACL($uri)) {
                return "<a href=\"{$uri}\" class='btn btn-xs btn-info'><i class='fa fa-fw fa-search'/></a>";
            }
        };
        $column->align("center");
        $column->index("[view]");
        $column->width(28);

        $this->columns[] = $column;
        return $c;
    }

    public function addDel($redirect = "")
    {
        $c = new RTColumn();
        $c->resizable = false;
        $c->align("center");
        $c->index("[del]");
        $c->width(28);
        $this->columns[] = $c;

        return $c;
    }

    public function addDels()
    {
        $c = new RTColumn();
        $c->align("center");
        $c->index("[dels]");
        $c->width(28);
        $column->descriptor[] = function ($obj) {
            if ($obj->canDelete()) {
                return "del";
            }
        };
        $this->columns[] = $c;
        return $c;
    }

    public function row()
    {
        return $this->row;
    }

    public function bind($func)
    {
        if ($func instanceof Iterator) {
            $this->objects = $objects;
        } else {
            $this->data_func = $func;
        }
    }

    public function attr($name, $value)
    {
        $this->_attribute[$name] = $value;
    }

    public function subHTML($func, $row_index = null)
    {
        $path = $func[0]->path();

        $url = $path . "/$func[1]";
        $this->subHTML = $url;
        return $url;
    }


    public function jsonSerialize()
    {
        $columns = $this->columns;
        $data = call_user_func($this->data_func, new RTRequest($this));
        $data["draw"] = $_GET["draw"];
        $objects = $data["data"];

        $ds = array();
        $row = array();
        foreach ($objects as $obj) {
            $r = [];

            $row = $this->row();
            if ($row->style) {
                //$r[]=[]
                $r["_row"]["style"] = call_user_func($row->style, $obj);
            }

            if ($this->pk) {
                $a = [];
                foreach ($this->pk as $key) {
                    $a[$key] = \My\Func::_($key)->call($obj);
                }
                $r["_pk"] = $a;
            } elseif ($this->key) {
                $r["_key"] = \My\Func::_($this->key)->call($obj);
            }

            if ($this->subHTML) {

                $r["[subhtml]"] = [
                    "type" => "sub-row",
                    "url" => $this->subHTML . "?id=" . \My\Func::_($this->key)->call($obj)
                ];
            }

            foreach ($columns as $k => $c) {

                if (sizeof($c->childs)) {
                    $result = "";
                    foreach ($c->childs as $child) {
                        $clone_obj = clone $child;
                        p($clone_obj)->data("object", $obj);
//                        $clone_obj->bind($obj);
                        $result .= (string)$clone_obj;
                    }
                    $r[$c->index] = $result;
                    continue;
                }

                if (isset($layout["visible"][$c->index]) && !$layout["visible"][$c->index]) {
                    continue;
                }
                if (is_object($obj)) {
                    $last_obj = $obj;
                } else {
                    $last_obj = call_user_func(array($this, $this->object_function), $obj);
                }

                $htmlspecialchars = false;
                $result = $obj;
                foreach ($c->descriptor as $descriptor) {
                    $result = \My\Func::_($descriptor)->call($result);
                    if (is_object($result)) {
                        $last_obj = $result;
                    }
                    if (is_string($descriptor)) {
                        $htmlspecialchars = true;
                    }
                }

                $d = [];

                if ($c->format) {
                    $result = \My\Func::_($c->format)->call($result);
                    $htmlspecialchars = false;
                }

                if ($c->alink && $last_obj) {
                    $htmlspecialchars = false;
                    $d["type"] = "link";
                    $d["href"] = $last_obj->uri($c->alink); 
                    // $a = PA::_()->href($last_obj->uri(PFunc::_($c->alink)->call($last_obj)))->text($result);
                    /*$p = new \P\Query("a");
                    $alink = $c->alink;
                    $p->attr("href", $last_obj->uri($alink));
                    $p->text($result);
                    $result = $p;*/
                }

                if ($htmlspecialchars) {
                    $result = htmlspecialchars($result);
                }

                if (count($c->descriptor)) {
                    $d["content"] = (string)$result;
                }

                if (isset($c->cell["css"])) {
                    if ($css = call_user_func($c->cell["css"], $obj)) {
                        $d['css'] = $css;
                    }
                }

                if (isset($c->cell["class"])) {
                    if ($class = call_user_func($c->cell["class"], $obj)) {
                        if (is_array($class)) {
                            $d["class"] = implode(" ", $class);
                        } else {
                            $d["class"] = $class;
                        }
                    }
                }

                if ($d["css"] || $d["class"] || $d["type"] == "link") {
                    $r[$c->index] = $d;
                } else {
                    $r[$c->index] = $d["content"];
                }

                if ($c->index === "[del]") {
                    if ($obj->canDelete()) {
                        $r[$c->index] = [];
                        $r[$c->index]["type"] = "delete";
                        $r[$c->index]["uri"] = $obj->uri("del");
                    } else {
                        $r[$c->index] = "";
                    }
                } elseif ($c->index === "[dels]") {
                    if ($obj->canDelete()) {
                        $r[$c->index] = [];
                        $r[$c->index]["type"] = "deletes";
                        $r[$c->index]["uri"] = $obj->uri("del");
                    }
                }

                if ($c->editable && $c->edit_type == "select") {
                    $index = $c->index;
                    $d = $r[$c->index];
                    unset($r[$c->index]);
                    $r[$c->index]["value"] = $obj->$index;
                    $r[$c->index]["content"] = $d;
                }

            }
            $ds[] = $r;
        }
        $data["data"] = $ds;
        return $data;
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

        $columns = $this->columns;
        $cols = [];
        $row = [];
        foreach ($columns as $k => $c) {
            if ($c->type == 'text') {
                $row[] = $c->label;
                $cols[] = $c->index;
            }
        }
        $writer->addRow($row);
        $data = $this->jsonSerialize();
        foreach ($data["data"] as $d) {
            $ds = [];
            foreach ($cols as $c) {
                if ($d[$c]["type"] == "link") {
                    $ds[$c] = $d[$c]["content"];
                } else {
                    $ds[$c] = $d[$c];
                }

            }
            $writer->addRow($ds);
        }

        $writer->close();
    }

    public function addButton($label)
    {
        $btn = new \BS\Button();
        $btn->classList[] = "btn-sm";
        $this->buttons[] = $btn;
        p($btn)->text($label);
        return $btn;
    }

    public function __toString()
    {
        if ($_GET["draw"]) {
            if ($_GET["type"]) {
                return $this->exportFile($_GET["type"]);
            }

            return json_encode($this->jsonSerialize(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }

        $uuid = "altrt" . uniqid();
        $rt = html("alt-rt");
        $rt->class("no-border");
        $rt->attr(["id" => $uuid]);
        $rt->attr($this->_attribute);
        if ($this->export_xlsx) {
            $rt->attr(["export-xlsx" => true]);
        }

        if ($this->export_csv) {
            $rt->attr(["export-csv" => true]);
        }

        if ($this->subHTML) {
            $rc = new \HTML\Node("rt-column");
            $rc->attr([
                "field" => "[subhtml]",
                "width" => "27px"
            ]);
            $rt->_child[] = $rc;
            $rt->_child[] = "\n";
        }

        $ui = \App\UI::_($this->_attribute["source"] );
        $layout=$ui->layout()["RT2"];

        foreach ($this->columns as $col) {
            $rc = new \HTML\Node("rt-column");

            $attr = [
                "title" => $col->label,
                "field" => $col->index,
                "searchable" => $col->searchable,
                "sortable" => $col->sortable,
                "editable" => $col->editable,
                "resizable" => $col->resizable,
                "fixed" => $col->fixed
            ];

            if($layout["visible"][$col->index]===false){
                $attr["hidden"]=true;
            }

            if ($col->align) {
                $attr["align"] = $col->align;
            }

            if ($col->index === "[dels]") {
                $attr["type"] = "deletes";
            }

            if ($col->index == $this->_attribute["sort-field"]) {
                $attr["sort-dir"] = $this->_attribute["sort-dir"];
            }


            if($col->wrap){
                $attr["wrap"]=true;
            }


            if ($col->width) {
                $attr["width"] = $col->width . "px";
                $attr["max-width"] = $col->width . "px";
            }

            if ($col->editable) {
                $attr["edit-type"] = $col->edit_type;
                if ($col->edit_type == "select") {
                    $data = [];
                    foreach ($col->edit_data as $k => $v) {
                        $data[] = [
                            "value" => $k,
                            "label" => $v
                        ];
                    }
                    $attr[":edit-data"] = $data;
                }
            }

            if ($col->searchable) {
                $attr["search-type"] = $col->search_type;

                if ($col->search_type == "select" || $col->search_type == "select2" || $col->search_type == "multiselect") {
                    $data = [];
                    $display_member = $col->searchOptions[1];
                    $value_member = $col->searchOptions[2];
                    if (!$value_member) {
                        $value_member = $column->index;
                    }
                    foreach ($col->searchOptions[0] as $k => $v) {
                        if (is_object($v)) {
                            $data[] = [
                                "label" => $display_member ? \My\Func::_($display_member)->call($v) : (string)$v,
                                "value" => \My\Func::_($value_member)->call($v)
                            ];
                        } else {
                            $data[] = ['label' => $v, 'value' => $k];
                        }
                    }
                    $attr[":search-option"] = json_encode($data);
                }

                $attr["search-multiple"] = $col->searchMultiple;
            }

            $rc->attr($attr);

            $rt->_child[] = $rc;
            $rt->_child[] = "\n";
        }


        if($this->buttons){
            $div=html("div");
            $div->slot("buttons");
            foreach($this->buttons as $btn){
                $div->_child[]=$btn;
            }
            $rt->_child[]=$div;
    
        }


        $html = (string)$rt;


        return $html;
    }

}