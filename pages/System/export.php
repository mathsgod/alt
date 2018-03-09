<?php
// Created By: Raymond Chong
// Created Date: 19/2/2010
// Updated date: 2017-06-01

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

use App\Module;
class System_export extends ALT\Page {
	public function post() {
		$module = Module::_($_POST["module"]);

		$name = $module->class;

		$table = $name::__table();

		if ($_POST["format"] == "CSV") {
			$writer = WriterFactory::create(Type::CSV);
			$writer->openToBrowser($table . ".csv");
		} else {
			$writer = WriterFactory::create(Type::XLSX);
			$writer->openToBrowser($table . ".xlsx");
		}

		$db = $name::__db();

		$col = [];
		foreach ($db->query("describe `{$table}`") as $rs) {
			$col[] = $rs["Field"];
		}
		$writer->addRow($col);

		foreach ($db->query("select * from `{$table}`") as $rs) {
			$writer->addRow($rs);
		}

		$writer->close();
	}

	public function get() {
		$mv = $this->createE();
        $data=App::Module();
        usort($data,function($a,$b){
            return $a->name>$b->name;
        });
		$mv->add("Target table")->select("module")->ds($data, "name", "name");
		$mv->add("Format")->select("format")->ds(["Excel" => "Excel", "CSV" => "CSV"]);

		$this->write($this->createForm($mv));
	}
}
