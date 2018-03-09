<?

class System_db extends ALT\Page {
	public function get() {
	   
       
       return;

		$bg = new ALT\BoxGroup();

		foreach (App::DB()->schema()->tables() as $table) {
            
            $t=$this->createT($table->columns());
            $t->header((string)$table);
            $t->add("Field","Field");
            $t->add("Type","Null");
            $t->add("Key","Key");
            $t->add("Default","Default");
            $t->add("Extra","Extra");
            
			$bg->addBox($t);
		}


		$b = new ALT\Box();
		$b->body()->append($bg);
		$this->write($b);


		return;

		$t = $this->createT(App::DB()->schema()->tables());
		$t->subHTML("", [$this, "ds"], "name");
		$t->add("Name", function ($o) {
			return $o; }
		);
		$this->write($t);
	}

	public function ds() {

	}
}
