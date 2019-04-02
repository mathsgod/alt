<?php
 // Created By: Raymond Chong
// Last Updated:
use App\Config;
use App\User;
use App\UserGroup;
use App\UserList;

class User_ae extends ALT\Page
{
    public function post()
    {
        parent::post();

        $obj = $this->object();
        if (isset($_POST["usergroup_id"])) {
            foreach ($_POST["usergroup_id"] as $usergroup_id) {
                $o = new UserList();
                $o->usergroup_id = $usergroup_id;
                $o->user_id = $obj->user_id;
                $o->save();
            }
        }
    }

    public function get()
    {



        $obj = $this->object();
        $mv = $this->createE();

        $c = $mv->add("Username");
        $user = $this->app->user;
        if ($user->isAdmin() || $user->isPowerUser() || !$obj->id()) {
            $c->input("username")->required();
        } else {
            $c->text("username");
        }

        $password = $mv->add("Password")->password("password")->minlength($this->app->config["user"]["password-length"]);
        if (!$obj->id()) {
            $password->required();
        }

        $mv->add("First name")->input("first_name")->required();
        $mv->add("Last name")->input("last_name");


        $mv->add("Phone")->input("phone");
        $mv->add("Email")->email("email")->required();

        $r = $mv->add("Address");
        $r->input("addr1");
        $r->input("addr2");
        $r->input("addr3");

        $r = $mv->add("Join date");
        if ($user->isAdmin() || $user->isPowerUser() || !$obj->id()) {
            $r->date("join_date")->required(true);
        } else {
            $r->date("join_date");
        }

        if (!$obj->isAdmin()) {
            if (($user->isAdmin() || $user->isPowerUser()) && $user->user_id != $obj->user_id) {
                $mv->add("Status")->select("status")->ds(User::STATUS);
                $mv->add("Expiry date")->date("expiry_date");
            }
        }

        if (($user->isAdmin() || $user->isPowerUser()) && !$obj->id()) {
            $mv->addHr();
            $u = UserGroup::_("Users");

            $ugs = UserGroup::find()->filter(function ($o) {
                if ($o->name == "Administrators" && !App::User()->isAdmin()) return false;
                return true;
            });

            $mv->add("User group")->multiSelect2("usergroup_id")->ds($ugs)->val([$u->usergroup_id]);
        }
        $mv->addHr();

        $mv->add("Language")->select("language")->ds($this->app->config["language"]);
        $mv->add("Default page")->input("default_page");
     
        //$f=$this->createForm("<div class='box'><div class='box-body'>body</abc></div>");

        $s=<<<EOT
    <div is="alt-e" class="form-horizontal">
        <div class="col-md-12">
           <div class="form-group">
              <label class="col-sm-2 control-label">Username</label>
              <div class="col-sm-10">
                 <div><input is="alt-input" class="form-control" name="username" data-field="username" value="" required="1"></div>
              </div>
           </div>
           <div class="form-group">
              <label class="col-sm-2 control-label">Password</label>
              <div class="col-sm-10">
                 <div><input is="bs-input" class="form-control" type="password" name="password" data-field="password" minlength="6" required="1"></div>
              </div>
           </div>
           <div class="form-group">
              <label class="col-sm-2 control-label">First name</label>
              <div class="col-sm-10">
                 <div><input is="alt-input" class="form-control" name="first_name" data-field="first_name" value="" required="1"></div>
              </div>
           </div>
           <div class="form-group">
              <label class="col-sm-2 control-label">Last name</label>
              <div class="col-sm-10">
                 <div><input is="alt-input" class="form-control" name="last_name" data-field="last_name" value=""></div>
              </div>
           </div>
           <div class="form-group">
              <label class="col-sm-2 control-label">Phone</label>
              <div class="col-sm-10">
                 <div><input is="alt-input" class="form-control" name="phone" data-field="phone" value=""></div>
              </div>
           </div>
           <div class="form-group">
              <label class="col-sm-2 control-label">Email</label>
              <div class="col-sm-10">
                 <div><input is="alt-email" name="email" data-field="email" value="" required="1"></div>
              </div>
           </div>
           <div class="form-group">
              <label class="col-sm-2 control-label">Address</label>
              <div class="col-sm-10">
                 <div><input is="alt-input" class="form-control" name="addr1" data-field="addr1" value=""><input is="alt-input" class="form-control" name="addr2" data-field="addr2" value=""><input is="alt-input" class="form-control" name="addr3" data-field="addr3" value=""></div>
              </div>
           </div>
           <div class="form-group">
              <label class="col-sm-2 control-label">Join date</label>
              <div class="col-sm-10">
                 <div><input is="alt-date" name="join_date" data-field="join_date" autocomplete="off" value="2019-04-02" required="1"></div>
              </div>
           </div>
           <div class="form-group">
              <label class="col-sm-2 control-label">Status</label>
              <div class="col-sm-10">
                 <div><select class="form-control" data-field="status" name="status" data-value="0"></select></div>
              </div>
           </div>
           <div class="form-group">
              <label class="col-sm-2 control-label">Expiry date</label>
              <div class="col-sm-10">
                 <div><input is="alt-date" name="expiry_date" data-field="expiry_date" autocomplete="off" value=""></div>
              </div>
           </div>
           <hr>
           <div class="form-group">
              <label class="col-sm-2 control-label">User group</label>
              <div class="col-sm-10">
                 <div><input type="hidden" name="usergroup_id"><select is="multiselect2" data-field="usergroup_id" name="usergroup_id[]" multiple></select></div>
              </div>
           </div>
           <hr>
           <div class="form-group">
              <label class="col-sm-2 control-label">Language</label>
              <div class="col-sm-10">
                 <div><select class="form-control" data-field="language" name="language" data-value=""></select></div>
              </div>
           </div>
           <div class="form-group">
              <label class="col-sm-2 control-label">Default page</label>
              <div class="col-sm-10">
                 <div><input is="alt-input" class="form-control" name="default_page" data-field="default_page" value=""></div>
              </div>
           </div>
        </div>
     </div>
EOT;

        $this->write($this->createForm($s));
        return;
        $this->write($this->createForm($mv));
    }
}
