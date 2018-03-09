<?php
use App\User;
use App\UserList;
class UserGroup_ae_user extends ALT\Page {
    public function post() {
        foreach($_POST["u"] as $user_id => $ug) {
            $u = new User($user_id);
        	$ids=[];
            foreach($u->UserList() as $ul) {
                $ids[] = $ul->usergroup_id;
                if (!in_array($ul->usergroup_id, $ug)) {
                    $ul->delete();
                }
            }

            foreach($ug as $usergroup_id) {
                if (in_array($usergroup_id, $ids))continue;
                $o = new UserList();
                $o->usergroup_id = $usergroup_id;
                $o->user_id = $u->user_id;
                $o->save();
            }
        }

        App::Msg("User list updated");
        App::Redirect("UserGroup/ae_user");
    }

    public function get() {
        $usergroups = App\UserGroup::find();

        $users = App\User::find()->filter(function($u) {
                return $u->user_id != App::UserID();
            }
            );

        return ["usergroups" => $usergroups, "users" => $users];
    }
}