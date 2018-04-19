<?php
use App\Config;
use App\System;
use App\User;

class _index extends ALT\Page\Login
{
    public function get($r)
    {
        if ($this->app->logined()) {
            if (App::User()->secret == "" && App::Config("user", "2-step verification")->value) {
                \App::Redirect("User/2step?auto_create=1");
                return;
            }

            if ($p = App::User()->default_page) {
                \App::Redirect($p);
            } else {
                \App::Redirect("Dashboard");
            }
            return;
        }

        if ($_COOKIE["app_username"]) {
            $user = User::first('username=' . App::DB()->quote($_COOKIE["app_username"]));

            if ($user) {
                header("location: lockscreen?username=" . $_COOKIE["app_username"]);
                return;
            }
        }

        $this->addLib("twbs/bootstrap");
        $this->addLib("components/font-awesome");
        $this->addLib("driftyco/ionicons");
        $this->addLib("iCheck");
        $this->addLib("bootboxjs");
        $data["title"] = Config::_("title");
        $data["company"] = Config::_("company");
        $data["logo"] = Config::_("logo");
        $data["version"] = App::Version();

        $data["r"] = $r;
        if (App::Config("user", "2-step verification")->value && !System::IP2StepExemptCheck($_SERVER['REMOTE_ADDR'])) {
            $data["ip2step"] = true;
        }

        return $data;
    }

}