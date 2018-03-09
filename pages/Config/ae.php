<?php

class Config_ae extends ALT\Page{
	public function get(){
		$mv=$this->createE();
		$mv->add("Name")->input("name")->required();
		$mv->add("Value")->input("value");

		$this->write($this->createForm($mv));
	}

}
