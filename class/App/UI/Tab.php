<?php
namespace App\UI;

use App\Page;

class Tab extends \P\HTMLDivElement
{
    public $navs;
    public $content;
    public static $_TabID = 0;
    public static $_MyTab = 0;
    protected $page;
    public $prefix;

    public function __construct(Page $page, $prefix)
    {
        parent::__construct();
        //$this->attributes["is"] = "alt-tab";
        $this->page = $page;
        $this->classList[] = "nav-tabs-custom";

        $this->navs = new \P\HTMLElement("ul");
        $this->navs->classList[] = "nav";
        $this->navs->classList[] = "nav-tabs";
        $this->append($this->navs);

        $this->content = new \P\HTMLDivElement();
        $this->content->classList[] = "tab-content";
        $this->append($this->content);

        self::$_MyTab++;
        $module = $page->module();
        $this->prefix = $prefix;

        if ($module) {
            $this->attributes["data-cookie"] = $page->path() . "/$prefix" . self::$_MyTab;
        }

        $this->classList[] = "my_tab";
    }

    public function collapsible()
    {
        return;
        $li = p("li")->addClass("pull-right");
        $a = p("a")->attr("href", "#")->html('<i class="fa fa-minus"></i>')->attr('data-widget', "collapse")->appendTo($li);
        p($this->navs)->append($li);
    }

    public function pinable()
    {
        return;
        $li = p("li")->addClass("pull-right");
        $a = p("a")->attr("href", "#")->html('<i class="fa fa-thumbtack"></i>')->appendTo($li);
        $a->attr("@click.prevent",'$emit("toggle-pin")');
        p($this->navs)->append($li);
        $this->attributes[":pinable"] = "true";
    }

    public function add($label, $uri, $t = null)
    {
        $module = $this->page->module();
        $label = $this->page->translate($label);
        self::$_TabID++;
        $tab_id = self::$_TabID;

        if ($id = $this->page->id()) {
            $href = $module->name . "/" . $id . "/" . $uri;
        } else {
            $href = dirname($this->page->path()) . "/" . $uri;
        }

        if (isset($t)) {
            $href .= "?t=$t";
        }
        $url = dirname($this->page->path()) . "/" . $uri;
        if (!\App\ACL::Allow($url)) {
            return;
        }
        $ti = new TabItem();
        //$li = p("li");

        $a = p("a")->attr("href", $href)->text($label)->appendTo($ti->li);
        $id = "tab-{$this->prefix}{$tab_id}";

        $a->attr("data-target", "#$id");
        $a->attr("data-toggle", "tabajax");
        $this->navs->append($ti->li);
        $div = p("div")->appendTo($this->content);
        $div->addClass("tab-pane")->attr("id", $id);
        return $ti;
    }

    public function addLocal($label, $content)
    {
        $label = $this->page->translate($label);
        self::$_TabID++;
        $tab_id = self::$_TabID;

        $i = new TabItem();

        $a = p("a")->attr("href", "#tab-{$tab_id}")->text($label)->appendTo($i->li);
        $a->attr("data-toggle", "tab");
        $this->navs->append($i->li);

        $i->div->attr("id", "tab-$tab_id")->append($content);
        $this->content->append($i->div);
        return $i;
    }
}
