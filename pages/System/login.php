<?php
class System_login extends R\Page
{
    public function post()
    {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $code = $_POST["code"];

        try {
            $this->app->login($username, $password, $code);
        } catch (Exception $e) {
            return ["code" => 400, "message" => $e->getMessage()];
        }

        return ["code" => 200];
    }

    public function get()
    {
        // redirect to dashboard
        $this->_redirect("");
    }

    public function forget_password()
    {
        $username = $_POST["username"];
        $email = $_POST["email"];
        if (!$username) {
            throw new \Exception("Username cannot be null", 400);
        }
        if (!$email) {
            throw new \Exception("Email cannot be null", 400);
        }

        $w[] = ["username=?", $username];
        $w[] = ["email=?", $email];
        if ($user = \App\User::first($w)) {
            $user->sendPassword();
        }

        return true;
    }
}
