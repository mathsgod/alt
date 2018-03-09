<?php
use App\UserLog;
class System_logout extends App\Page {
    public function get() {
        if (App\System::Logined()) {
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

?>