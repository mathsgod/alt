<?php
class SystemBackup implements \My\Object {
    public $filename;

    public function __construct($id) {
        $this->filename = $id . ".sql";
    }

	public function canCreate(){
		return true;
	}

    public function id() {
        $pi = pathinfo($this->filename);
        return $pi['filename'];
    }

    public function delete() {
        unlink(CMS_ROOT . "/backup/" . $this->filename);
        return true;
    }

    public function canDelete() {
        return true;
    }

    public function canUpdate() {
        return true;
    }

    public function canRead() {
        return true;
    }

    public function restore() {
        $dbname = Setting::_("database", "username");
        $dbuser = Setting::_("database", "database");
        $dbpassword = Setting::_("database", "password");

        system("mysql -u {$dbuser} -p{$dbpassword} {$dbname} < " . CMS_ROOT . "/backup/" . $this->filename);
    }

    public function uri($v = "") {
        $id = $this->id();
        if ($v) {
            return "SystemBackup/$id/" . $v;
        } else {
            return "SystemBackup/$id";
        }
    }
}

?>