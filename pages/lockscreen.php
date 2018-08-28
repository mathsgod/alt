<?php
use App\Config;
use App\System;
use App\User;
class lockscreen extends ALT\Page\LockScreen {
    public function get($username) {
        $app=$this->app;
        if ($app->logined()) {
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
        $this->addLib("hostlink/font-awesome");
        $this->addLib("driftyco/ionicons");
        $this->addLib("iCheck");

        $data["user"] = $user;
        $data["company"] = $app->config["user"]["company"];
        $data["domain"] = $app->config["user"]["domain"];
        $data["name"] = (string)$user;
        $data["copyright"]["year"] = $app->config["user"]["copyright-year"];
        $data["copyright"]["url"] = $app->config["user"]["copyright-url"];

        return $data;
    }

    public function login() {
        setcookie("app.username", "", time() - 3600);
        header("location: index");
    }
}