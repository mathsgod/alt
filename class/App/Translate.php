<?php
namespace App;
class Translate extends Model {
	private static $_CACHE = [];
	public static function ByModule($module, $language) {
		if (self::$_CACHE[$module][$language])return self::$_CACHE[$module][$language];
		$data = [];

		$lang = explode("-", $language);

		if ($ini = App::_()->getFile("translate.ini")) {
			$ini = parse_ini_file($ini, true);
			if ($ini[$lang[0]])$data = array_merge($data, $ini[$lang[0]]);
			if ($ini[$language])$data = array_merge($data, $ini[$language]);
		}

		if ($ini = App::_()->getFile("pages/translate.ini")) {
			$ini = parse_ini_file($ini, true);
			if ($ini[$lang[0]])$data = array_merge($data, $ini[$lang[0]]);
			if ($ini[$language])$data = array_merge($data, $ini[$language]);
		}

		if ($ini = App::_()->getFile("pages/{$module}/translate.ini")) {
			$ini = parse_ini_file($ini, true);
			if ($ini[$lang[0]])$data = array_merge($data, $ini[$lang[0]]);
			if ($ini[$language])$data = array_merge($data, $ini[$language]);
		}


		$w=[];
		$w[] = "module is null";
		$w[] = "language=" . self::__db()->quote($language);

		foreach(Translate::Find($w) as $translate) {
			$data[$translate->name] = $translate->value;
		}

		$w=[];
		$w[] = "module=" . self::__db()->quote($module);
		$w[] = "language=" . self::__db()->quote($language);

		foreach(Translate::Find($w) as $translate) {
			$data[$translate->name] = $translate->value;
		}

		self::$_CACHE[$module][$language] = $data;
		return $data;
	}

	public static function _($name, $language) {
		$w[] = "name=" . self::__db()->quote($name);
		$w[] = "language=" . self::__db()->quote($language);

		$t = Translate::first($w);
		if (!$t) {
			// find in ini
			$translate_ini = parse_ini_file(SYSTEM . "/translate.ini", true);

			if (isset($translate_ini[$language][$name])) {
				return $translate_ini[$language][$name];
			}
			return $name;
		} else {
			return $t->value;
		}
	}

	public function __toString() {
		return $this->value;
	}

	public function delete() {
		// delete other
		$w[] = ["name=?",$this->name];
		if ($this->module) {
			$w[] = ["module=?",$this->module];
		} else {
			$w[] = "module is null";
		}

		if ($this->action) {
			$w[] = ["action=?",$this->action];
		} else {
			$w[] = "action is null";
		}

		Translate::Query()->where($w)->delete()->execute();
	}

	public function get($language) {
		$w[] = "name=" . self::__db()->quote($this->name);
		$w[] = "language=" . self::__db()->quote($language);
		if ($this->module) {
			$w[] = "module=" . self::__db()->quote($this->module);
		} else {
			$w[] = "module is null";
		}

		if ($this->action) {
			$w[] = "action=" . self::__db()->quote($this->action);
		} else {
			$w[] = "action is null";
		}

		return Translate::first($w);
	}
}

?>