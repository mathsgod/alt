<?php
// Created By: Raymond Chong
// Created Date: 19/2/2010
// Last Updated:
class SystemBackup_do_run extends App\Page {
    public function get() {
        $dbname = App::Config("database", "username");
        $dbuser = App::Config("database", "database");
        $dbpassword = App::Config("database", "password");

        system("mysqldump --user {$dbuser} --password={$dbpassword} {$dbname} > " . getcwd() . "/backup/" . date("YmdHis") . ".sql");
        App::Redirect("SystemBackup");
    }
}

?>