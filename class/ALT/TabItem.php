<?php
namespace ALT;

class TabItem {
    public $li;
    public $div;

    public function __construct() {
        $this->li = p("li");
        $this->div = p("div");
        $this->div->addClass("tab-pane");
    }

    public function active() {
        $this->li->addClass('active');
        $this->div->addClass('active');
    }

    public function addBadge($text) {
        // <span data-toggle="tooltip" title="abc" class="badge bg-yellow" data-original-title="3 New Messages" aria-describedby="tooltip357532">3</span>
        $span = p("span");
        $span->addClass("badge");

        $span->text($text);

        $this->li->find("a")->append(" ");
        $this->li->find("a")->append($span);
        return $span;
    }
}

?>