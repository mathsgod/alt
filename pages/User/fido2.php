<?php
use R\WebAuthn;

class User_fido2 extends ALT\Page
{
    public function post()
    {
        $weba = new WebAuthn($_SERVER['HTTP_HOST']);
        $user = $this->app->user;
        $user->credential = json_encode($weba->register($_POST));
        $user->save();
        return ["code" => 200, "username" => $user->username];
    }

    public function getCredential()
    {
        $weba = new \WebAuthn($_SERVER['HTTP_HOST']);
        return ["data" => $weba->prepare_challenge_for_registration($this->app->user->username, $this->app->user->user_id . "")];
    }

    public function get()
    {
    }
}