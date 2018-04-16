<?php
use App\User;

class User_list extends ALT\Page
{
    public function get()
    {
        // outp(App\User::find());
        
        $rt = $this->createRT([$this, "ds"]);
        //$rt->attr("selectable",true);
//        $rt->attr("responsive",false);
        //$rt->attr("page-size",50);
        $rt->attr("cell-url", "User");
        $rt->key("user_id");
        $rt->addEdit();
        $rt->addDel();

        $rt->add("Username", "username")->search()->alink("v")->sort()->nowrap();
        $rt->add("User group", function ($obj) {
            return $obj->UserGroup()->implode(",");
        })->searchOption(App\UserGroup::find(), null, "usergroup_id")->searchCallback(function ($v) {
            return ["user_id in (select user_id from UserList where usergroup_id=?)", $v];
        });
        $rt->add("First name", "first_name")->ss();
        $rt->add("Last name", "last_name")->ss();
        $rt->add("Phone", "phone")->ss()->editable();
        $rt->add("Email", "email")->ss();//->overflow("hidden");
        $rt->add("Status", "Status()")->index("status")->sort()->searchSelect2(User::$_Status);
//        $rt->add("Status", "Status()")->index("status")->sort()->searchOption(User::$_Status);
        // $rt->add("Expiry date", "expiry_date")->sort()->searchDateRange();
        $rt->add("Expiry date", "expiry_date")->sort()->searchDate();
        $rt->add("Join date", "join_date")->sort()->searchDate();
        $rt->add("Language", "language")->nowrap()->sort();
        $rt->add("Skin", "skin")->nowrap()->sort();

        $rt->add("Online", "isOnline()")->format("tick");
         //$rt->addButton("test")->attr("onClick","window.self.location='User/1/v'");
        // $rt->add("Style","style")->attr("data-format",'json')->attr("collapsed",true);
        $this->write($rt);
    }

    public function ds($rt)
    {
        $w = $rt->where();

        if ($_GET["t"] >= 0) $w[] = ["status=?", $_GET["t"]];
        return [
            "total" => User::count($w),
            "data" => User::find($w, $rt->order(), $rt->limit())
        ];
    }
}