<?
namespace App\UI;

use Closure;

class DataTables extends \P\HTMLElement
{
    private $columns = [];
    private $objects = null;

    public $paging = true;
    public $ajax = null;
    public $serverSide = false;
    public $searching = true;
    public $ordering = true;
    public $responsive = false;
    public $processing = true;
    public $scrollX = false;
    public $order = [];

    public $response = null;

    public $_order = [];
    public $select = true;

    public $dom = "<'row'<'col-sm-6'l><'col-sm-6'>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>";

    public $buttons = ['print', 'copy', 'excel', 'pdf'];

    public $fixedHeader = ["header" => true];

    public function __construct($objects)
    {
        parent::__construct("alt-datatables");

        $this->objects = $objects;
     //   $this->attributes["is"] = "alt-datatables";

        $this->response = new DTResponse($objects);
    }

    public function boxStyle()
    {
        $this->dom = "<'box no-border'<'box-body'" .
            "<'row'<'col-sm-6'l><'col-sm-6'>>" .
            "<'row'<'col-sm-12'tr>>" .
            "<'row'<'col-sm-5'i><'col-sm-7'p>>" .
            "<'row'<'col-sm-12'B>>" .
            ">>";
        return $this;
    }

    public function order($data, $dir)
    {
        $this->_order[] = [$data, $dir];
        return $this;
    }

    public function addEdit()
    {
        $c = $this->response->addEdit();
        $this->columns[] = $c;
        return $c;
    }

    public function addView()
    {
        $c = $this->response->addView();
        $this->columns[] = $c;
        return $c;
    }

    public function addDel()
    {
        $c = $this->response->addDel();
        $this->columns[] = $c;
        return $c;
    }

    public function add($title, $getter)
    {
        $c = new Column();
        $c->title = $title;
        $c->descriptor[] = $getter;


        if ($getter instanceof Closure) {
            $c->data = md5(new \ReflectionFunction($this->getter));
            $c->name = $c->data;
        } else {
            $c->data = $getter;
            $c->name = $getter;
        }

        $c->data = str_replace(["(", ")"], "_", $c->data);

        $this->columns[] = $c;

        return $c;
    }

    public function _data()
    {
        $data = [];
        foreach ($this->objects as $k => $obj) {
            $d = [];
            foreach ($this->columns as $col) {
                $d[$col->data] = (string)$col->getData($obj, $k);
            }
            $data[] = $d;
        }
        return $data;
    }

    public function __toString()
    {

        $this->attributes["data-searching"] = $this->searching ? "true" : "false";
        $this->attributes["data-columns"] = $this->columns;
        $this->attributes["data-paging"] = $this->paging ? "true" : "false";
        $this->attributes["data-responsive"] = $this->responsive ? "true" : "false";
        $this->attributes["data-processing"] = $this->processing ? "true" : "false";
        $this->attributes["data-scroll-x"] = $this->scrollX ? "true" : "false";
        $this->attributes["data-dom"] = $this->dom;
        $this->attributes[":buttons"] = $this->buttons;

        $this->attributes["data-select"] = $this->select ? "true" : "false";

        if ($this->fixedHeader) {
            $this->attributes["data-fixed-header"] = $this->fixedHeader;
        }

        $order = [];
        foreach ($this->_order as $o) {
            foreach ($this->columns as $i => $c) {
                if ($c->data == $o[0]) {
                    $order[] = [$i, $o[1]];
                    continue;
                }
            }
        }


        $this->attributes["data-order"] = $order;

        if ($this->ajax) {
            if ($this->serverSide) {

                $cs = [];
                foreach ($this->columns as $c) {
                    $cs[] = ["searchType" => $c->searchType];
                }
                $this->ajax["data"]["_columns"] = $cs;

                $this->attributes["data-server-side"] = "true";
            }
            $this->attributes["data-ajax"] = $this->ajax;
        } else {
            $this->attributes["data-data"] = $this->_data();
        }




        return parent::__toString();
    }

}