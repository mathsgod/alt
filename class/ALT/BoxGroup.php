<?

namespace ALT;
class BoxGroup extends \P\HTMLDivElement {

	public function __construct() {
		parent::__construct();
		$this->classList->add("box-group");
		$this->attributes["id"] = uniqid();
		
	}

	public function addBox($box) {
		p($box)->addClass("panel");
		p($this)->append($box);

		$div = p("div");
		$div->attr("id", uniqid());
		$div->addClass("panel-collapse collapse");
		$box->body()->wrap($div);

		$a = p("a");
		$a->attr("data-toggle", "collapse");
		$a->attr("data-parent", "#" . $this->attributes["id"]);
		$a->attr("href", "#" . $div->attr("id"));
		p($box)->find(".box-title")->wrapInner($a);


		return $this;
	}

}
