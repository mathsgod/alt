<?php
// Created By: Raymond Chong
// Created Date: 2013-04-10
// Last Updated:
use App\UserList;
class User_v_usergroup extends App\Page {
    public function get() {
        $rt = $this->createRT([$this, "ds"], "UserGroup");
        $rt->add("UserGroup", "UserGroup()");
        $this->write($rt);
    }

    public function ds($r) {
        $w[] = "user_id=" . $this->object()->user_id;
        $d["total"] = UserList::Count($w);
        $d["data"] = UserList::find($w, $r->order(), $r->limit());

        return $d;
    }
}

?>