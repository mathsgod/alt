<?php
namespace App;
class UserList extends Model {
    public function canDelete() {
        if ($this->usergroup_id == 1) {
            if (!\App::User()->is("Administrators")) {
                return false;
            }
        }
        return parent::canDelete();
    }
}

?>