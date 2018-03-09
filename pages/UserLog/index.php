<?php

class UserLog_index extends ALT\Page {
	public function get() {
//		$this->header("User Log");
		$tab = $this->createTab();
		$tab->add("All user log","list");
		$this->write($tab);
	}
}