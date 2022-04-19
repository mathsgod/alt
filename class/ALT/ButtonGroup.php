<?php

namespace ALT;

class ButtonGroup extends \BS\ButtonGroup
{
	protected $page;
	public function __construct(Page $page)
	{
		parent::__construct();
		$this->page = $page;
	}

	public function addButton($label = null, $uri = null)
	{
		$b = parent::addButton($this->page->translate($label));
		$b->classList[] = "btn-primary";
		if ($uri) {
			$b->href($uri);
		}
		return $b;
	}
}
