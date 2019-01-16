<?php
use App\Config;
use App\System;
use App\User;

class _index extends ALT\Page\Login
{
    public function get($r)
    {

        $config = $this->app->config;
        if ($this->app->logined()) {
            $user = $this->app->user;

            if ($user->secret == "" && $config["user"]["2-step verification"]) {
                $this->response = $this->response->withHeader("Location", "User/2step?auto_create=1");
                return;
            }

            if ($p = $user->default_page) {
                $this->response = $this->response->withHeader("Location", $p);
            } else {
                $this->response = $this->response->withHeader("Location", "Dashboard");
            }

            return;
        }

        $this->addLib("iCheck");

        $pi = $this->app->pathInfo();

        $data["system_base"] = $pi["system_base"];
        $data["title"] = $config["user"]["title"];
        $data["company"] = $config["user"]["company"];
        $data["logo"] = $config["user"]["logo"];
        $data["version"] = $this->app->version();


        $data["r"] = $r;
        if ($config["user"]["2-step verification"] && !System::IP2StepExemptCheck($_SERVER['REMOTE_ADDR'])) {
            $data["ip2step"] = true;
        }

        return $data;
    }

    public function __call($name, $args)
    {
        if ($this->app->logined()) {
            $this->response = $this->response->withHeader("Location", "404_not_found");
        } else {
            $base = $this->request->getUri()->getBasePath();
            $this->response = $this->response->withHeader("Location", $base . "/?r=" . $name);
        }
    }

}