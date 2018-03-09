<?php
use App\Config;
use App\System;
use App\User;
class lockscreen extends ALT\Page\LockScreen {
    public function get($username) {
        if (App\System::Logined()) {
        	header("location: index");
            return;
        }

        if (!$username) {
            setcookie("app.username", "", time() - 3600);
            header("location: index");
            return;
        }

        $user = User::first([['username=?',$username]]);
        if (!$user) {
            setcookie("app.username", "", time() - 3600);
            header("location: index");
            return;
        }
        $this->addLib("twbs/bootstrap");
        $this->addLib("components/font-awesome");
        $this->addLib("driftyco/ionicons");
        $this->addLib("iCheck");

        $data["user"] = $user;
        $data["company"] = App::Config("user", "company");
        $data["domain"] = App::Config("user", "domain");
        $data["name"] = (string)$user;
        $data["copyright"]["year"] = App::Config("user", "copyright-year");
        $data["copyright"]["url"] = App::Config("user", "copyright-url");

        return $data;
    }

    public function login() {
        setcookie("app.username", "", time() - 3600);
        header("location: index");
    }
}