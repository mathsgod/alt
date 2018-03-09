<?php
use App\UserGroup;
class UserGroup_list extends App\Page {
    public function get() {
        $rt = $this->createRT([$this, "ds"]);
        $rt->addEdit();
        $rt->addDel();
        $rt->key("usergroup_id");
        $rt->add("Usergroup ID", "usergroup_id")->sort()->searchEq();
        $rt->add("Name", "name")->sort()->alink("v")->search();
        $rt->add("Code", "code")->sort()->search();
        $rt->add("User count", "_Size(UserList)");
        $rt->subHTML([$this, "user"]);

        $this->write($rt);
    }

    public function ds($rt) {
        $w = $rt->where();
        $d["total"] = UserGroup::Count($w);
        $d["data"] = UserGroup::find($w, $rt->order(), $rt->limit());
        return $d;
    }

    public function user($id) {
        $ug = new UserGroup($id);
        $t = $this->createT($ug->User());

        $t->add("Username")->a("username")->href(function($o) {
                return $o->uri("v");
            }
            );
        $t->add("First name", "first_name");
        $t->add("Last name", "last_name");
        $t->add("Email", "email");
        $this->write($t);
    }
}