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

    public function __construct($objects)
    {
        parent::__construct("alt-datatables");

        $this->objects = $objects;
     //   $this->attributes["is"] = "alt-datatables";
    }

    public function addEdit()
    {
        $c = new Column();
        $c->title = "";
        $c->type = "edit";
        $c->data = "__edit__";
        $c->name = "__edit__";
        $c->width = "10px";
        $this->columns[] = $c;
        return $c;
    }

    public function addView()
    {
        $c = new Column();
        $c->title = "";
        $c->type = "view";
        $c->data = "__view__";
        $c->name = "__view__";
        $c->width = "10px";
        $this->columns[] = $c;
        return $c;
    }

    public function addDel()
    {
        $c = new Column();
        $c->title = "";
        $c->type = "del";
        $c->data = "__del__";
        $c->name = "__del__";
        $c->width = "10px";
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
                $d[$col->index()] = $col->getData($obj, $k);
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