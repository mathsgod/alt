<?php
namespace App;
class UserGroup extends Model {
    public function User() {
        return $this->UserList()->map(function($o) {
                return $o->User();
            }
            );
    }

    public static function _($name) {
        if ($g = UserGroup::first("name='$name'")) {
            return $g;
        }
        if ($g = UserGroup::first("code='$name'")) {
            return $g;
        }

        return null;
    }

    public function hasUser($user) {
        foreach($user->UserList() as $ul) {
            if ($ul->usergroup_id == $this->usergroup_id)return true;
        }
        return false;
    }

    public function __toString() {
        return $this->name;
    }

	public function canUpdate(){
		if ($this->usergroup_id <= 4)return false;
		return parent::canUpdate();
	}

    public function canDelete() {
        if ($this->_Size("UserList"))return false;
        if ($this->usergroup_id <= 4)return false;
        return parent::canDelete();
    }
}

?>