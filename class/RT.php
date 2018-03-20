<?php
use App\UI;

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
    public $sortable = false;
    public $visible = true;
    public $searchOptions = null;
    public $alink = null;
    public $descriptor = [];
    public $align = null;
    public $editable = false;

    public $editable_type;
    public $sort_index = null;

    public $max_width = null;
    public $min_width = null;

    public $wrap = true;
    public $cell = [];
    public $rt;
    public $overflow;
    public $css = [];

    public function __construct($rt)
    {
        $this->rt = $rt;
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
    public function editable($type = "input", $data = null)
    {
        $this->editable = true;
        $this->editable_type = $type;
        $this->editable_data = $data;
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
        $this->search_callback = $callback;
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
        if ($index instanceof Closure) {
            $index = md5(new ReflectionFunction($index));
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
            $d = json_decode($_COOKIE[$this->rt->uri()], true);
            $search_remember = $d["search_remember"];
        }

        if ($this->search_type == "date") {
            $data = [];
            if (isset($_GET["search"][$this->index . "_from"])) {
                $data["from"] = $_GET["search"][$this->index . "_from"];
                if ($search_remember) {
                    $_SESSION["app"]["rt"][$this->rt->uri()]["search"][$this->index]["from"] = $data["from"];
                }
            } else {
                $data["from"] = $_SESSION["app"]["rt"][$this->rt->uri()]["search"][$this->index]["from"];
            }

            if (isset($_GET["search"][$this->index . "_to"])) {
                $data["to"] = $_GET["search"][$this->index . "_to"];
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
                    $order[] = "(" . call_user_func($col->sort_callback) . ") " . $dir;
                } else {
                    if (in_array($column["column"], $columns_index)) {
                        $order[] = $column["column"] . ' ' . $dir;
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
                    $where[] = ["$name >= ?", $from["value"]];
                }
                if ($value["to"] != "") {
                    $where[] = ["$name <= ?", $to["value"]];
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

class RTHeader extends \P\Query
{
    public function __construct()
    {
        parent::__construct("div");
    }

    public function addButton()
    {
        $btn = new \BS\Button();
        $btn->addClass("btn-sm");
        $this->append($btn);

        return $btn;
    }
}

class RTExport
{
    public $buttons = [];
    public function addCSV($getter)
    {
        $li = new P\Query("li");
        $li->attr("data-type", "csv");
        $a = new P\Query("a");
        $li->append($a);

        $a->text('CSV');

        $this->buttons[] = $li;
        return $li;
    }

    public function __toString()
    {
        $html = "";
        $html .= "<div class='btn-group rt-export'>";
        $html .= '<button class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" type="button"><i class="fa fa-download"></i> <span class="caret"></span></button>';
        $html .= '<ul class="dropdown-menu">';

        foreach ($this->buttons as $btn) {
            $html .= $btn;
        }
        $html .= '</ul>';
        $html .= "</div>";
        return $html;
    }
}

class RT extends \P\Query implements \JsonSerializable
{
    public $length = 25;
    public $order_index;
    public $order_dir;
    public $row;
    public $_uri;

    public $header;

    public $export;
    public $request;

    public function header()
    {
        if ($this->header) {
            return $this->header;
        }
        $this->header = new RTHeader();
        return $this->header;
    }

    public function jsonSerialize()
    {
        $columns = $this->columns($_GET["uri"]);
        $ui = UI::_($_GET["uri"]);
        $layout = $ui->layout();
        if ($_GET["length"] != 25 || isset($layout["length"])) {
            $layout["length"] = $_GET["length"];
            $ui->layout = json_encode($layout);
            $ui->save();
        }

        if ($_GET["order"]) {
            foreach ($_GET["order"] as $i => $order) {
                $column = $columns[$order["column"]];
                $layout["order"] = array($column->name => $order["dir"]);
                $ui->layout = json_encode($layout);

                $ui->save();
            }
        }
        $data = call_user_func($this->data_func, new RTRequest($this));
        $data["draw"] = $_GET["draw"];

        $objects = $data["data"];

        $ds = array();
        $row = array();
        foreach ($objects as $obj) {
            $r = $this->row();
            if ($r->style) {
                $row[] = array("style" => call_user_func($r->style, $obj));
            }

            $r = array();

            if ($this->pk) {
                $a = [];
                foreach ($this->pk as $key) {
                    $a[$key] = My\Func::_($key)->call($obj);
                }
                $r["_pk"] = $a;
            } elseif ($this->key) {
                $r["_key"] = My\Func::_($this->key)->call($obj);
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
                    $result = My\Func::_($descriptor)->call($result);
                    if (is_object($result)) {
                        $last_obj = $result;
                    }
                    if (is_string($descriptor)) {
                        $htmlspecialchars = true;
                    }
                }

                if ($c->format) {
                    $result = My\Func::_($c->format)->call($result);
                    $htmlspecialchars = false;
                }
                if ($c->alink && $last_obj) {
                    $htmlspecialchars = false;
                    // $a = PA::_()->href($last_obj->uri(PFunc::_($c->alink)->call($last_obj)))->text($result);
                    $p = new \P\Query("a");
                    $alink = $c->alink;
                    $p->attr("href", $last_obj->uri($alink));
                    $p->text($result);
                    $result = $p;
                }
                if ($htmlspecialchars) {
                    $result = htmlspecialchars($result);
                }

                $d = [];
                $d["content"] = (string)$result;

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

                if ($d["css"] || $d["class"]) {
                    $r[$c->index] = $d;
                } else {
                    $r[$c->index] = $d["content"];
                }
            }
            $ds[] = $r;
        }
        $data["data"] = $ds;

        $data["row"] = $row;

        return $data;
    }

    public function __construct($request = null)
    {
        $this->request = $request;
        // if (API::User()->setting("table-condensed"))
        parent::__construct("table");
        $this->addClass("table-condensed");
        $this->addClass("RT");
        $this->attr('responsive', true);
        $this->addClass("table");
        $this->addClass("table-bordered");
        $this->addClass("table-hover");
        $this->attr("paging", true);

//        $this->css("table-layout","fixed");
        
        // if (My::Setting("rt-font-size")) {
        // $this->css("font-size", My::Setting("rt-font-size"));
        // }
        $this->row = new RTRow();
        $this->attr("data-url", $_SERVER["REQUEST_URI"]);
        // filter number
        $uris = array_filter(explode("/", $_SERVER["REQUEST_URI"]), function ($u) {
            return !is_numeric($u);
        });
        $this->_uri = implode("/", $uris);

        $this->export = new RTExport();
    }

    public function uri()
    {
        if ($_GET["uri"]) {
            return $_GET["uri"];
        }
        return $this->_uri;
    }

    public function subHTML($func, $row_index = null)
    {
        $path = $func[0]->path();

        $url = $path . "/$func[1]";
        $this->subHTML = $url;
        return $url;
    }

    public function order($index, $dir = "asc")
    {
        $this->order_index = $index;
        $this->order_dir = $dir;
        return $this;
    }

    public function bind($func)
    {
        if ($func instanceof Iterator) {
            $this->objects = $objects;
        } else {
            $this->data_func = $func;
        }
    }

    public $button = array();

    public function addButton($label)
    {
        $btn = new \BS\Button();
        $btn->classList[] = "btn-sm";
        $this->buttons[] = $btn;
        p($btn)->text($label);
        return $btn;
    }

    public function addCheckbox($index)
    {
        $column = new RTColumn();
        $column->descriptor[] = function ($obj) use ($index) {
            $input = p("input");
            $input->attr("type", "checkbox");
            $input->addClass("iCheck");

            $uri = parse_url($_SERVER["REQUEST_URI"]);
            $path = $uri["path"];

            if ($index) {
                $input->attr("index", $index);
                $input->val($obj->$index);
                $input->attr("name", "{$index}[]");
            }
            $input->attr("onClick", "$(this).trigger('change')");
            $input->attr('onChange', "$(this).closest('.RT').data('rt').checkboxChange(this)");
            return $input;
        };

        $column->label = "<input type='checkbox' class='iCheck' onClick='
var checked=this.checked;
var index=$(this).closest(\"th\").index();
var td=$(this).closest(\"table\").find(\"tbody tr\").find(\"td:nth(\"+index+\")\");
if(checked){
	td.find(\".iCheck\").iCheck(\"check\");
}else{
	td.find(\".iCheck\").iCheck(\"uncheck\");
}
td.find(\".iCheck\").trigger(\"change\");

'/>";

        $column->align("center");
        $column->index("[checkbox]");
        $column->width(27);
        $this->columns[] = $column;
        return $c;
    }

    public function addEdit()
    {
        $column = new RTColumn();
        // $column->wdith(20);
        $column->descriptor[] = function ($obj) {
            $uri = $obj->uri('ae');
            if ($obj->canUpdate() && App::ACL($uri)) {
                // return "<a href=\"{$uri}\">" . Icon::_("edit") . "</a>";
                return "<a href=\"{$uri}\" class='btn btn-xs btn-warning'><i class='fa fa-pencil-alt'/></a>";
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
        $column->descriptor[] = function ($obj) {
            $uri = $obj->uri('v');
            if ($obj->canRead() && App::ACL($uri)) {
                return "<a href=\"{$uri}\" class='btn btn-xs btn-info'><i class='fa fa-search'/></a>";
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
        // $c->width(20)->align("center")->fixed(true);
        $c->descriptor[] = function ($obj) use ($redirect) {
            $url = $obj->uri('del');
            if ($obj->canDelete()) {
                if ($redirect != "") {
                    $url .= "?redirect={$redirect}";
                }
                $a = p("a");
                $a->attr("href", $url);
                $a->addClass('btn btn-xs btn-danger');
                $a->html("<i class='fa fa-times'></i>");
                $a->attr("onClick", "

if(confirm('Are your sure?')){
	$.getJSON('$url').done(function(){
		$(this.closest('table.RT')).data('rt').refresh();
	}.bind(this)).fail(function(jqXHR){
		bootbox.alert(jqXHR.responseJSON.message);
	});;
}

return false;

");
                return $a;
            }
        };

        $c->align("center");
        $c->index("[del]");
        $c->width(28);
        $this->columns[] = $c;

        return $c;
    }
    // add row, and return cell
    public function add($label, $callback = null)
    {
        $column = new RTColumn($this);
        // $column->label = T::_($label);
        $column->label = htmlentities($label);
        $column->index($callback);
        $column->descriptor[] = $callback;
        // $column->Column()->attr("name", $callback);
        $this->columns[] = $column;
        // if($callback)$column->gf($callback);
        /*$r = new My\Library("RT");
		   $ini = parse_ini_file(CMS_ROOT . "/" . $r->path . "/config.ini", true);
		   if (isset($ini["width"][$label])) {
		   $column->width($ini["width"][$label]);
		   }*/

        return $column;
    }

    private function columns($uri)
    {
        // filter number
        $uris = array_filter(explode("/", $uri), function ($u) {
            return !is_numeric($u);
        });

        $uri = implode("/", $uris);

        $layout = UI::_($uri)->layout();
        $order = $layout["visible"];
        if (count($order) == 0) {
            // API::output($this->columns);
            return $this->columns;
        }
        $order = array_flip(array_keys($order));
        $columns = array();
        foreach (array_reverse($this->columns) as $column) {
            $seq = $order[$column->index];
            if (is_null($seq)) {
                $seq = PHP_INT_MAX;
            }
            $columns[] = array($seq, $column);
        }
        usort($columns, function ($a, $b) {
            if ($a[0] === $b[0]) {
                return 0;
            }
            return ($a[0] < $b[0]) ? -1 : 1;
        });

        return array_map(function ($c) {
            return $c[1];
        }, $columns);
    }

    public function row()
    {
        return $this->row;
    }

    public function key($key)
    {
        $this->key = $key;
        return $this;
    }

    public function pk($key)
    {
        $this->pk = $key;
    }

    public function object($func)
    {
        $this->object_function = $func;
    }

    public function request()
    {
        return new RTRequest();
    }

    public function export()
    {
        return $this->export;
    }

    public function __toString()
    {
        if ($_GET["draw"]) {
            foreach ($this->columns as $column) {
                $column->rt = $this;
            }

            try {
                return json_encode($this->jsonSerialize(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            } catch (Exception $e) {
                $data["code"] = $e->getCode();
                $data["message"] = $e->getMessage();
                return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            }
        }
        $columns = $this->columns($this->_uri);
        // API::output($columns);
        $ui = UI::_($this->_uri);
        $layout = $ui->layout();
        $visible = array();
        foreach ($columns as $i => $column) {
            if ($layout["visible"][$column->index] || !isset($layout["visible"][$column->index])) {
                $visible[$i] = true;
            } else {
                $visible[$i] = false;
            }
        }

        $this->addClass($this->css_class);

        if ($this->order_index) {
            $this->attr("order", $this->order_index);
            $this->attr("order-dir", $this->order_dir);
        }

        $head = p("<thead></thead>")->appendTo($this);
        $tr = p("<tr></tr>")->appendTo($head);
        // ---- columns (thead) --------
        // sub html
        if ($this->subHTML) {
            $th = p("<th></th>")->appendTo($tr);
            $th->attr("width", 24);
            $th->css("min-width", "20px");
            $th->css("max-width", "20px");
            $th->addClass("visible");
            $th->addClass("subhtml");
            $th->attr("data-url", $this->subHTML);
            $th->attr("cell-align", "center");
        }


        foreach ($columns as $i => $column) {
            $th = p("<th></th>")->html($column->label)->appendTo($tr);
            $th->attr("title", $column->label);
            $th->addClass("rt-col");
            $th->css("white-space", "pre-wrap");
            if (!$visible[$i]) {
                $th->addClass("hide");
            } else {
                $th->addClass("visible");
            }

            if ($column->sortable) {
                if ($column->sort_index) {
                    $th->attr("sort-index", $column->sort_index);
                } else {
                    $th->attr("sort-index", $column->index);
                }
            }

            if ($column->index == $this->order_index) {
                if ($this->order_dir == "asc") {
                    $th->addClass("sorting_asc");
                } else {
                    $th->addClass("sorting_desc");
                }
            }

            $th->attr("data-index", $column->index);
            if ($column->width) {
                // $th->css("max-width", $column->width."px");
                $th->attr("width", $column->width);
                $th->css("min-width", $column->width . "px");
                $th->css("max-width", $column->width . "px");
                $th->attr("cell-style", json_encode(array("word-wrap" => "break-word", "white-space" => "pre-wrap")));
            }

            if ($column->overflow == "hidden") {
                $th->attr("cell-style", json_encode(["overflow" => "hidden", "white-space" => "nowrap", "text-overflow" => "ellipsis"]));
            }

            $cell_style = array();
            if ($column->min_width) {
                // $th->width($column->max_width);
                $cell_style["word-wrap"] = "break-word";
                $cell_style["white-space"] = "pre-line";
                $cell_style["min-width"] = $column->min_width . "px";
                $th->css("min-width", $column->min_width . "px");
            }
            if ($column->max_width) {
                $cell_style["word-wrap"] = "break-word";
                $cell_style["white-space"] = "pre-line";
                $cell_style["max-width"] = $column->max_width . "px";
                $th->css("max-width", $column->max_width . "px");
            }

            if ($column->wrap) {
                // word-wrap: break-word; white-space: pre-wrap;
                $cell_style["word-wrap"] = "break-word";
                $cell_style["white-space"] = "pre-wrap";
            }

            if (count($cell_style)) {
                $th->attr("cell-style", json_encode($cell_style));
            }

            if ($column->attr) {
                $th->attr("cell-attr", json_encode($column->attr));
            }

            if ($column->editable) {
                $th->addClass("editable");
                $th->attr("editable-type", $column->editable_type);
                if ($column->editable_type == "select") {
                    $th->attr("editable-data", json_encode($column->editable_data));
                }
            }

            if ($column->sortable) {
                $th->addClass("sortable");
            }

            if ($column->className) {
                $th->className($column->className);
            }

            if ($column->align) {
                $th->attr("cell-align", $column->align);
            }

            foreach ($column->css as $k => $v) {
                $th->css($k, $v);
            }
            // $th->addClass("all");
            // $th->attr($column->Column()->attr());
            if ($column->c) {
                foreach ($column->c->_tag_list as $t) {
                    if (($t->tag() == "input") || ($t->tag() == "textarea") || ($t->tag() == "select")) {
                        if (!$column->c->index) {
                        } elseif (!$column->c->index instanceof Closure) {
                            $t->name("{$column->c->index}");
                        }
                    }
                }
                $th->attr('c-tpl', (string)$column->c->render(new stdClass()));
            }
        }
        // -- column (search row)
        $hasSearch = false;
        foreach ($columns as $i => $column) {
            if ($column->searchable) {
                $hasSearch = true;
                break;
            }
        }

        if ($hasSearch) {
            $tr = p("tr")->appendTo($head);
            if ($this->subHTML) {
                $cell = p("td")->appendTo($tr);
            }

            foreach ($columns as $i => $column) {
                $cell = p("td")->appendTo($tr);
                $cell->attr("data-index", $column->index);

                if (!$visible[$i]) {
                    $cell->addClass("hide");
                }

                if ($column->max_width) {
                    // $th->width($column->max_width);
                    $cell->css(array("word-wrap" => "break-word", "white-space" => "pre-wrap", "max-width" => $column->max_width . "px"));
                }

                if ($column->searchable) {
                    if ($column->search_type == 'range') {
                        $g = new \BS\InputGroup();
                        $g->input()->addClass("search")->attr("name", $column->index . "_from")->attr("placeholder", "from");
                        $g->button()->addClass("search-clear-btn")->html('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>');
                        $g->addClass("input-group-sm");
                        $cell->append($g);

                        $g = new \BS\InputGroup();
                        $g->input()->addClass("search")->attr("name", $column->index . "_to")->attr("placeholder", "to");
                        $g->button()->addClass("search-clear-btn")->html('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>');
                        $g->addClass("input-group-sm");
                        $cell->append($g);
                    } elseif ($column->search_type == "daterange") {
                        $input = p("input")->addClass("rt_daterangepicker search");
                        $input->attr("name", $column->index);
                        $input->addClass("form-control");
                        $cell->append($input);
                    } elseif ($column->search_type == "date") {
                        $cell->css("overflow", "hidden");

                        $value = $column->getSearchValue();

                        $g = new \BS\InputGroup();
                        // $g->css("width","100px");
                        $input = $g->input()->addClass("search")->attr("name", $column->index . "_from")->attr("placeholder", "from")->addClass("date"); //->addClass("input-sm");
                        if ($value["from"] != "") {
                            $input->val($value["from"]);
                        }
                        $g->button()->addClass("search-clear-btn")->html('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>');
                        $g->addClass("input-group-sm");
                        // $g->css('width',"100%");
                        $cell->append($g);
                        // $cell->append(PInput::_()->addClass("form-control input-sm")->addClass("search")->attr("name",$column->index."_from")->placeholder("from")->addClass("date"));
                        $g = new \BS\InputGroup();
                        // $g->css("width","100px");
                        $input = $g->input()->addClass("search")->attr("name", $column->index . "_to")->attr("placeholder", "to")->addClass("date"); //->addClass("input-sm");
                        if ($value["to"] != "") {
                            $input->val($value["to"]);
                        }
                        $g->button()->addClass("search-clear-btn")->html('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>');
                        $g->addClass("input-group-sm");
                        $cell->append($g);
                        // $cell->append(PInput::_()->addClass("form-control input-sm")->addClass("search")->attr("name",$column->index."_to")->placeholder("to")->addClass("date"));
                    } elseif ($column->searchOptions) {
                        $select = new My\HTML\Select();

                        $select->attr("index", $column->index);
                        $select->addClass("search");

                        if ($column->search_type == "select2") {
                            $select->addClass("select2");
                        }



                        $select->ds($column->searchOptions[0], $column->searchOptions[1], $column->searchOptions[2]);
                        $select->prepend("<option></option>");
                        $select->attr("name", $column->index);
                        $select->attr("search", $column->index);

                        $value = $column->getSearchValue();
                        if ($value != "") {
                            $select->val($value);
                        }

                        $cell->append((string)$select);
                    } else {
                        $input = P\Query::_("input")->addClass("form-control input-sm search")->attr("name", $column->index);

                        if ($value = $column->getSearchValue()) {
                            $input->attr("value", $value);
                        }

                        $cell->append("<div>" . $input . "</div>");
                    }
                } else {
                    $cell->append("<div></div>");
                }
            }
        }

        $form_name = "";
        if ($this->attr('form-name') != "") {
            $form_name = $this->attr('form-name');
        }

        if ($layout["length"]) {
            $this->attr("length", $layout["length"]);
        } else {
            $this->attr("length", $this->length);
        }

        $header = $this->header();
        $this->attr('rt-header', $header->html());

        $html = "<div class='box box-default no-border'>\n";

        if ($this->attr("responsive") === false) {
            $html .= "<div class='box-body no-padding table-responsive'>\n" . parent::__tostring() . "\n</div>\n";
        } else {
            $html .= "<div class='box-body no-padding'>\n" . parent::__tostring() . "\n</div>\n";
        }



        $html .= "<div class='box-footer'>\n";

        if ($this->export->buttons) {
            $html .= (string)$this->export;
        }

        if ($this->buttons) {
            $html .= "<div class='btn-group'>";
            foreach ($this->buttons as $btn) {
                $html .= $btn;
            }
            $html .= "</div>\n";
        }

        $html .= "</div>\n";

        $html .= "</div>\n";

        return $html;
    }
}
