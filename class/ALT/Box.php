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

class Box extends \P\HTMLDivElement
{
    private static $NUM = 0;

    public static $_BOX_CLASS = ["box-default", "box-primary", "box-success", "box-info", "box-warning", "box-danger"];

    protected $page = null;
    private $_header = null;
    private $_body = null;
    private $_footer = null;

    public $name = "";
    public $check_acl = false;

    public $pinable=true;

    public function __construct($page)
    {
        parent::__construct();
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
                if ($layout["collapse"]) {
                    $this->collapsible($layout["collapse"]);
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
        $this->check_acl = true;
        $this->name = $name;
        if (!\App::User()->isAdmin()) {
            return;
        }
        $uri = $this->page->action . "/box[$name]";
        $module = $this->page->module->name;
        $bd = $this->header()->tools()->addButtonDropdown();
        p($bd->button())->html("<i class='fa fa-lock'></i>");
        foreach (\App\UserGroup::find() as $ug) {
            $w = [];
            $w[] = ["path=?", $uri];
            $w[] = ["usergroup_id=?", $ug->usergroup_id];
            $o = $bd->addItem($ug, "javascript:void(0)");
            if ($ug->name == 'Administrators') {
                $o->addClass("disabled");
                $o->addClass("checked");
            }
            if (\App\ACL::Count($w)) {
                $o->addClass("checked");
            }
            $o->find("a")->prepend("<i class='fa fa-check'></i>");

            $o->find("a")->attr("onClick", "
if($(this).parent().hasClass('checked')){
	$.ajax('ACL/box?module=" . $module . "&usergroup_id=" . $ug->usergroup_id . "&path=" . urlencode($uri) .
                "&remove=1" . "');	
	$(this).parent().removeClass('checked');				
}else{
	$.ajax('ACL/box?module=" . $module . "&usergroup_id=" . $ug->usergroup_id . "&path=" . urlencode($uri) . "');
	$(this).parent().addClass('checked');	
}");
        }
    }

    public function pinable()
    {
        if (!$this->pinable) {
            return $this;
        }
        $header = $this->header();
        foreach (p($header)->find("button") as $btn) {
            if ($btn->attributes["data-widget"] == "pin") {
                return $this;
            }
        }
        $btn = $header->tools()->addButton();
        p($btn)->attr("data-widget", "pin")->append('<i class="fa fa-thumb-tack"></i>');

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

        if ($this->check_acl) {
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

    public function collapsible($collapsed = false)
    {
        if ($this->collapsible) {
            if (func_num_args() == 0) {
                return $this;
            }
            p($this->header()->tools())->find("button")->each(function ($i, $o) use ($collapsed) {
                $p = p($o); if ($p->attr("data-widget") == "collapse") {
                    if ($collapsed) {
                        $p->find("i")->removeClass("fa-minus")->addClass("fa-plus");
                    } else {
                        $p->find("i")->removeClass("fa-plus")->addClass("fa-minus");
                    }
                }
            }
            );

            return $this;
        }
        $this->collapsible = true;
        if ($collapsed) {
            $this->classList->add("collapsed-box");
        } else {
            $this->classList->remove("collapsed-box");
        }

        $header = $this->header();
        if ($collapsed) {
            $btn = $header->tools()->addButton();
            p($btn)->attr("data-widget", "collapse")->append('<i class="fa fa-plus"></i>');
        } else {
            $btn = $header->tools()->addButton();
            p($btn)->attr("data-widget", "collapse")->append('<i class="fa fa-minus"></i>');
        }

        return $this;
    }
}
