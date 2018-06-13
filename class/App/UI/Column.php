<?
namespace App\UI;

use JsonSerializable;
use Closure;

class Column implements JsonSerializable
{
    public $name;
    public $title;
    public $data;
    public $orderable = false;
    public $searchable = false;
    public $searchType = "text";
    public $searchOptions = [];
    public $type = "text";
    public $alink = null;
    public $descriptor = [];
    public $width = null;
    public $className = [];

    public $format = null;
    public $searchCallback = null;

    public $editable = false;
    public $editType = 'text';
    public $editData;

    public function editable($type = "text", $data)
    {
        $this->editable = true;
        $this->editType = $type;
        $this->editData = $data;
        if (is_array($data)) {
            $this->editData = [];
            foreach ($data as $k => $v) {
                $this->editData[] = ["value" => $k, "label" => $v];
            }
        }
        return $this;
    }

    public function format($format)
    {
        $this->format = $format;
        $this->raw = true;
        return $this;
    }

    public function gf($descriptor)
    {
        $this->descriptor[] = $descriptor;
        return $this;
    }

    public function alink($alink = null)
    {
        $this->alink = $alink;
        return $this;
    }

    public function ss()
    {
        $this->orderable = true;
        $this->searchable = true;
        return $this;
    }

    public function search()
    {
        $this->searchable = true;
        return $this;
    }

    public function sort()
    {
        return $this->order();
    }

    public function order()
    {
        $this->orderable = true;
        return $this;
    }

    public function searchCallBack($callback)
    {
        $this->searchable = true;
        $this->searchCallback = $callback;
        return $this;
    }

    public function searchSelect2($objects, $display_member, $value_member)
    {
        $this->searchable = true;
        $this->searchOptions = array($objects, $display_member, $value_member);
        $this->searchType = 'select2';
        return $this;
    }

    public function searchDate()
    {
        $this->searchable = true;
        $this->searchType = 'date';
        return $this;
    }

    public function searchMultiple($objects, $display_member, $value_member)
    {
        $this->searchable = true;
        $this->searchOptions = array($objects, $display_member, $value_member);
        $this->searchType = 'multiselect';
        $this->searchMultiple = true;
        return $this;
    }

    public function searchOption($objects, $display_member, $value_member)
    {
        $this->searchable = true;
        $this->searchOptions = array($objects, $display_member, $value_member);
        $this->searchType = 'select';
        return $this;
    }

    public function _searchOption()
    {
        $data = [];
        $display_member = $this->searchOptions[1];
        $value_member = $this->searchOptions[2];
        if (!$value_member) {
            $value_member = $this->data;
        }
        foreach ($this->searchOptions[0] as $k => $v) {
            if (is_object($v)) {
                $data[] = [
                    "label" => $display_member ? \My\Func::_($display_member)->call($v) : (string)$v,
                    "value" => \My\Func::_($value_member)->call($v)
                ];
            } else {
                $data[] = ['label' => $v, 'value' => $k];
            }
        }
        return $data;
    }

    public function getData($object, $k)
    {
        $result = $object;
        foreach ($this->descriptor as $descriptor) {
            $result = \My\Func::_($descriptor)->call($result);
            if (is_object($result)) {
                $last_obj = $result;
            }
            if (is_string($descriptor)) {
                $htmlspecialchars = true;
            }
        }

        if ($this->format) {
            $result = \My\Func::_($this->format)->call($result);
            $htmlspecialchars = false;
        }


        if ($this->alink && $last_obj) {
            $htmlspecialchars = false;

            $a = html("a")->href($last_obj->uri($c->alink));
            $a->text($result);
            $result = $a;
        }
        return $result;
    }

    public function jsonSerialize()
    {
        $data = [];
        $data["name"] = $this->name;
        $data["title"] = $this->title;
        $data["data"] = $this->data;
        $data["orderable"] = $this->orderable;
        $data["searchable"] = $this->searchable;
        $data["searchType"] = $this->searchType;
        $data["searchOption"] = $this->_searchOption();
        $data["editable"] = $this->editable;
        $data["editType"] = $this->editType;
        $data["editData"] = $this->editData;
        if ($this->width) $data["width"] = $this->width;
        if ($this->className) $data["className"] = implode(" ", $this->className);
        return $data;
    }


}

