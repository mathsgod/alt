<?php

class Translate_tree extends App\Page {
	public function get() {

		$t = new My\TreeView();

		foreach (App\Module::All() as $module) {
			$folder = $t->addFolder($module);
			foreach (App::Language() as $lang => $language) {
				$f1 = $folder->addFolder($language);

				foreach (App\Translate::ByModule($module->name, $lang) as $k => $v) {
					$f1->addFile($k . " → " . $v);
				}
			}
			// outp($m);
		}

		$this->write($t);
	}
}
