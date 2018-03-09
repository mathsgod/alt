<?php
use App\User;

class User_list2 extends ALT\Page
{
    public function get()
    {
        $rt = new ALT\RT();
        $rt->bind([$this, "ds"]);
        $rt->attr("cell-url", "User");
        $rt->key("user_id");
        $rt->addEdit();
        $rt->addDel();
        $rt->attr("responsive",false);
/*        $rt->row()->style(function () {
            return ["background-color" => "red"];
        });*/

        $rt->order("username","desc");

        $rt->add("Username", "username")->fixed();//->search()->alink("v")->sort()->nowrap();
/*        $rt->add("User group", function ($obj) {
            return $obj->UserGroup()->implode(",");
        })->searchOption(App\UserGroup::find(), null, "usergroup_id")->searchCallback(function ($v) {
            return ["user_id in (select user_id from UserList where usergroup_id=?)", $v];
        });*/

        $rt->add("User group", function ($obj) {
            return $obj->UserGroup()->implode(",");
        });/*->searchMultiple(App\UserGroup::find(), null, "usergroup_id")->searchCallback(function ($v) {
            return ["user_id in (select user_id from UserList where usergroup_id=?)", $v];
        });*/

        $rt->add("First name", "first_name");//->ss();
        $rt->add("Last name", "last_name");//->ss();
        $rt->add("Phone", "phone");//->ss()->editable();
        $rt->add("Email", "email");//->ss();//->overflow("hidden");
        $rt->add("Status", "Status()");//->index("status")->sort()->searchSelect2(User::$_Status)->editable("select", User::$_Status);
        $rt->add("Expiry date", "expiry_date");//->sort()->searchDate();
        $rt->add("Join date", "join_date");//->sort()->searchDate()->editable("date");
        $rt->add("Language", "language");//->nowrap()->sort();
        $rt->add("Skin", "skin");//->nowrap()->sort();

//        $rt->subHTML([$this, "test"]);
        $this->write($rt);
    }

    public function ds($rt)
    {
        $w = $rt->where();
        return [
            "total" => App\User::Count($w),
            "data" => App\User::Find($w, $rt->order(), $rt->limit())
        ];
    }

    public function test($id)
    {
        $rt = new ALT\RT();
        $rt->bind([$this, "ds"]);
        $rt->add("Username", "username");
        $this->write($rt);
    }


}