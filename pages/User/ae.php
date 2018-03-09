<?php
// Created By: Raymond Chong
// Last Updated:
use App\Config;
use App\User;
use App\UserGroup;
use App\UserList;
class User_ae extends ALT\Page {
    public static $_Status = [0 => "Active", 1 => "Inactive"];

    public function post() {
        $obj = $this->object()->bind($_POST);
        $obj->save();

        if (isset($_POST["usergroup_id"])) {
            foreach($_POST["usergroup_id"] as $usergroup_id) {
                $o = new UserList();
                $o->usergroup_id = $usergroup_id;
                $o->user_id = $obj->user_id;
                $o->save();
            }
        }
        App::Msg("User updated");
        App::Redirect($obj->uri("v"));
    }

    public function get() {
        $obj = $this->object();
        $mv = $this->createE();
        
        $c = $mv->add("Username");
        if (My::User()->isAdmin() || My::User()->isPowerUser() || !$obj->id()) {
            $c->input("username")->required();
        } else {
            $c->text("username");
        }

        $password = $mv->add("Password")->password("password")->minlength(Config::_("password-length"));
        if (!$obj->id()) {
            $password->required();
        }

        $mv->add("First name")->input("first_name")->required();
        $mv->add("Last name")->input("last_name");

        $mv->Add("Phone")->input("phone");
        $mv->Add("Email")->email("email")->required();

        $r = $mv->add("Address");
        $r->input("addr1");
        $r->input("addr2");
        $r->input("addr3");

        $r = $mv->add("Join date");
        if (App::User()->isAdmin() || App::User()->IsPowerUser() || !$obj->id()) {
            $r->date("join_date")->required(true);
        } else {
            $r->date("join_date");
        }

        if (!$obj->isAdmin()) {
            if ((App::User()->isAdmin() || App::User()->IsPowerUser()) && App::User()->user_id != $obj->user_id) {
                $mv->add("Status")->select("status")->ds(User::$_Status);
                $mv->add("Expiry date")->date("expiry_date");
            }
        }

        if ((App::User()->isAdmin() || App::User()->is("Power Users")) && !$obj->id()) {
            $mv->addHr();
            $u = UserGroup::_("Users");

            $ugs = UserGroup::find()->filter(function($o)use($obj, &$selected) {
                    if ($o->name == "Administrators" && !App::User()->isAdmin())return false;
                    if ($o->hasUser($obj)) {
                        $selected[] = $o->usergroup_id;
                    }
                    return true;
                }
                );

        	$mv->add("User group")->multiSelect2("usergroup_id")->ds($ugs)->val([$u->usergroup_id]);
        }
        $mv->addHr();

        $mv->add("Language")->select("language")->ds(App::Language());
        $mv->add("Default page")->input("default_page");

        $form = $this->createForm($mv)->action("");
        $this->write((string)$form);
    }
}