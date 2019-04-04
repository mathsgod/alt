<?

namespace ALT;

class TimelineItem extends \P\HTMLDivElement
{
	public $icon;
	public $header, $body, $footer;

	public function __construct()
	{
		parent::__construct();
		$this->classList->add("timeline-item");
	}

	public function header()
	{
		if (!$this->header) {
			$this->header = p("h3")->addClass("timeline-header")->appendTo($this)[0];
		}
		return p($this->header);
	}

	public function body()
	{
		if (!$this->body) {
			$this->body = p("div")->addClass("timeline-body")->appendTo($this)[0];
		}
		return p($this->body);
	}

	public function footer()
	{
		if (!$this->footer) {
			$this->footer = p("div")->addClass("timeline-footer")->appendTo($this)[0];
		}
		return p($this->footer);
	}

	public function setIcon($class)
	{
		if (!$this->icon) {
			$this->icon = p("i");
			$this->icon->addClass($class);
			p($this)->prepend($this->icon);
		}

		return $this->icon;
	}
}

class TimeLabel extends \P\HTMLElement
{
	public $span;
	public function __construct()
	{
		parent::__construct("li");
		$this->classList->add("time-label");
		$this->span = p("span")->appendTo($this)[0];
	}

	public function addClass($class)
	{
		p($this->span)->addClass($class);
		return $this;
	}

	public function text($text)
	{
		p($this->span)->text($text);
		return $this;
	}
}

class Timeline extends \P\HTMLElement
{
	public function __construct()
	{
		parent::__construct("ul");
		$this->classList->add("timeline");
	}

	public function addLabel()
	{
		return p(new TimeLabel())->appendTo($this)[0];
	}

	public function addItem()
	{
		$li = p("li")->appendTo($this);
		$item = new TimelineItem();
		p($item)->appendTo($li);
		return $item;
	}

	public function addEnd()
	{
		$icon = p('<i class="fa fa-clock-o bg-gray"></i>');
		$li = p("li")->appendTo($this);
		$li->append($icon);
		return $icon;
	}
}
