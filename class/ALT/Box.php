<?php

namespace ALT;

class BoxClassTokenList extends \P\DOMTokenList
{
    public function offsetSet($offset, $value)
    {
        if (in_array($value, Box::$_BOX_CLASS)) {
            $this->token = array_diff($this->token, Box::$_BOX_CLASS);
        }
        parent::offsetSet($offset, $value);
    }
}

class Box extends \P\Element
{
    private static $NUM = 0;

    public static $_BOX_CLASS = ["box-default", "box-primary", "box-success", "box-info", "box-warning", "box-danger"];

    protected $page = null;
    private $_header = null;
    private $_body = null;
    private $_footer = null;

    public $name = "";
    public $checkACL = false;

    public $pinable = true;

    public function __construct($page)
    {
        parent::__construct("alt-box");
        $this->classList = new BoxClassTokenList;

        $this->attributes["box-num"] = self::$NUM;
        $this->classList->add("box");
        $this->classList->add("box-primary");

        $this->page = $page;

        if ($page) {
            $uri = $page->path() . "/box[" . self::$NUM . "]";
            $this->attributes["data-uri"] = $uri;
            // load layout
            $ui = \App\UI::_($uri);
            if ($ui->layout) {
                $layout = json_decode($ui->layout, true);
                if ($layout["collapsed"]) {
                    $this->collapsible($layout["collapsed"]);
                }
            }
        }

        self::$NUM++;

    }

    private $collapsible = false;

    public function addClass($class)
    {
        p($this)->addClass($class);
        return $this;
    }

    public function setACL($name)
    {
        $this->checkACL = true;
        $page = $this->page;
        $path = $page->path() . "/box[" . $name . "]";

        $this->attributes["data-acl-uri"] = $path;

        if (\App::User()->isAdmin()) {
            $header = $this->header();
            $ugs = [];
            foreach (\App\UserGroup::find() as $ug) {
                if ($ug->name == 'Administrators') {
                    $ugs[] = [
                        "usergroup_id" => $ug->usergroup_id,
                        "name" => $ug->name,
                        "selected" => true,
                        "disabled" => true
                    ];
                    continue;
                }

                $u = [];
                $u["usergroup_id"] = $ug->usergroup_id;
                $u["name"] = $ug->name;
                $u["selected"] = false;

                $w = [];
                $w[] = ["path=?", $path];
                $w[] = ["usergroup_id=?", $ug->usergroup_id];
                $w[] = "value='allow'";

                if (\App\ACL::Count($w)) {
                    $u["selected"] = true;
                }

                $ugs[] = $u;
            }
            $header->attributes[":acl-group"] = $ugs;
        }
    }

    public function pinable()
    {
        $this->header()->attributes["pinable"] = true;
        return $this;
    }

    public function header($title = null)
    {
        if (!$this->_header) {
            $this->_header = new BoxHeader($this->page);
            p($this)->prepend($this->_header);
        }
        if ($title) {
            $this->_header->setTitle($title);
        }
        return $this->_header;
    }

    public function body()
    {
        if (!$this->_body) {
            $this->_body = new BoxBody($this->page);
            if ($this->_footer) {
                $this->_footer->before($this->_body);
            } else {
                p($this)->append($this->_body);
            }
        }
        return p($this->_body);
    }

    public function footer()
    {
        if (!$this->_footer) {
            $this->_footer = new BoxFooter($this->pager);
            $this->_footer->appendTo($this);
        }
        return $this->_footer;
    }

    public function canRead()
    {

        if ($this->checkACL) {
            if (\App::User()->isAdmin()) {
                return true;
            }

            //check allow
            $module = $this->page->module->name;
            $uri = $this->page->action . "/box[{$this->name}]";
            foreach (\App::User()->UserGroup() as $ug) {
                $w = [];
                $w[] = ["module=?", $module];
                $w[] = ["path=?", $uri];
                $w[] = ["usergroup_id=?", $ug->usergroup_id];
                if (\App\ACL::Count($w)) {
                    return true;
                }
            }

            return false;
        }

        return true;
    }

    public function __toString()
    {
        if (!$this->canRead(\App::User())) {
            return "";
        }

        return parent::__toString();
    }

    public function collapsible($collapsed)
    {
        $this->attributes["collapsible"] = true;

        if (isset($collapsed)) {
            $this->attributes["collapsed"] = $collapsed;
        }

        return $this;
    }
}
