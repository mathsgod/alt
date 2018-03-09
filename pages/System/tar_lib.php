<?php
// Created By: Raymond Chong
// Created Date: 24/2/2010
// Last Updated: 2013-04-10
class System_tar_lib extends ALT\Page {
	public function post() {
		chdir($_POST['library']);
		$name = basename($_POST['library']);
		$path = $_POST["path"] . "/$name.gz";
		if (file_exists($path)) {
			unlink($path);
		}

		`tar -zcvf {$path} *`;
		App::Msg("{$_POST['library']} tar successfully");
		App::Redirect("System/tar_lib");
	}

	public function get() {
		$mv = My::E();
		$mv->add("Library")->select("library")->options(glob("/home/vhosts/raymond2/public_html/cms/plugins/*"));
		$mv->add("Path")->input("path")->val("/home/vhosts/raymond2/public_html/cms4_source/plugins");
		$this->write($this->createForm($mv));
	}
}

?>
