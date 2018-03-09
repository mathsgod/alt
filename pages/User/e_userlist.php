<?php
use App\UserGroup;
use App\UserList;
class User_e_userlist extends ALT\Page {
    public function post() {
        $obj = $this->object();
        $ids = [];
        foreach($obj->UserList() as $ul) {
            $ids[] = $ul->usergroup_id;
            if (!in_array($ul->usergroup_id, $_POST["usergroup_id"])) {
                $ul->delete();
            }
        }

        foreach($_POST["usergroup_id"] as $usergroup_id) {
            if (in_array($usergroup_id, $ids))continue;
            $o = new UserList();
            $o->usergroup_id = $usergroup_id;
            $o->user_id = $obj->user_id;
            $o->save();
        }
        App::Msg("User updated");
        App::Redirect();
    }

    public function get() {
        $obj = $this->object();

        $mv = $this->createE();
        $mv->add("Name", "__toString()");

        $ugs = UserGroup::find()->filter(function($o) {
                if ($o->name == "Administrators" && !App::User()->isAdmin())return false;
                return true;
            }
            );

        $ug = $obj->UserList()->map(function($o) {
                return $o->usergroup_id;
            }
            )->asArray();
        $mv->add("User Group")->multiSelect2()->ds($ugs, null, "usergroup_id")->val($ug)->attr("name", "usergroup_id[]");
        $this->write($this->createForm($mv));
    }
}

?>