<?php
use App\UserLog;
use App\User;
class UserLog_list extends App\Page {
    public function get() {
        $jq = $this->createRT([$this, "ds"]);

        $jq->order("userlog_id", "desc");
        $jq->add("ID", "userlog_id")->sort()->searchEq();
        $jq->add("User", "User()")->index("user_id")->searchOption(User::find(), null, "user_id");
        $jq->add("Login time", "login_dt")->sort()->searchDate();
        $jq->add("Logout time", "logout_dt")->sort()->searchDate();
        $jq->add("IP address", "ip")->sort()->search();
        $jq->add("Result", "result")->sort()->searchOption(array("SUCCESS" => "SUCCESS", "FAIL" => "FAIL"));
        $jq->add("User agent", "user_agent")->search();

        $this->write($jq);
    }

    public function ds($jq) {
        $w = $jq->where();

        return array("total" => UserLog::count($w),
            "data" => UserLog::find($w, $jq->Order(), $jq->Limit()));
    }
}

?>