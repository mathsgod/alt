<?php
// Created By: Raymond Chong
// Created Date: 19/2/2010
// Last Updated:
class SystemBackup_index extends ALT\Page {
    public function get() {
        // check right
        $folder = getcwd() . "/backup";
        $c = substr(sprintf('%o', fileperms($folder)), - 4);
        if ($c != "0777") {
            $this->callout("Warning", "Permission of " . getcwd() . "/backup is not correct ($c), please change the permission of folder to 0777", "warning");
        } else {
        	$this->navbar()->addButton("Backup now", "SystemBackup/do_run")->addClass("confirm btn-primary");
        }

        $mtb = $this->createTab();
        $mtb->add("All Backup", "list");
        $this->write($mtb);
    }
}

?>