<?php
// Created By: Raymond Chong
// Created Date: 20/8/2010
// Last Updated:
class SystemBackup_restore extends Page {
    public function get() {
        $this->object()->Restore();
        API::Msg("Database restored");
        API::Redirect("SystemBackup");
    }
}

?>