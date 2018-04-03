<?php
use App\UserLog;

class System_logout extends App\Page
{
    public function get()
    {
        if (App\System::Logined()) {


            if ($_SESSION["app"]["org_user"]) {
                $_SESSION["app"]["user"] = new App\User($_SESSION["app"]["org_user"]->user_id);
                unset($_SESSION["app"]["org_user"]);
            }

            App::User()->offline();
            $user_id = App::UserID();
            $w[] = "user_id=$user_id";
            if ($ul = UserLog::first($w, ["userlog_id", "desc"])) {
                $ul->logout_dt = date("Y-m-d H:i:s");
                $ul->save();
            }
        }

        $_SESSION["app"] = [];
        App::Redirect("");
    }
}