<?php

namespace ALT;

class ButtonDropdown extends \BS\ButtonDropdown
{
	protected $page;

	public function __construct(Page $page, $label)
	{
		$this->page = $page;
		parent::__construct($page->translate($label));
	}
}
