<?php
// Created By: Raymond Chong
// Created Date: 2013-04-10
// Last Updated:
namespace User;
class v_userlog extends \App\Page {
    public function get() {
        $jq = $this->createRT([$this, "ds"], "App\UserLog");
        $jq->order("userlog_id", "desc");
        $jq->add("Login time", "login_dt")->searchDate()->sort();
        $jq->add("Logout time", "logout_dt");
        $jq->add("IP address", "ip")->search();
        $jq->add("Result", "result")->search();
        $jq->add("User agent", "user_agent")->search()->wrap();
        $this->write($jq);
    }

    public function ds($jq) {
        $obj = $this->object();
        $search = $jq->Search();
        if ($search["ip"] != "")$w[] = "ip like '%{$search['ip']}%'";
        if ($search["result"] != "")$w[] = "result like '%{$search['result']}%'";
        if ($search["login_dt_from"] != "")$w[] = "login_dt >= '{$search['login_dt_from']}'";
        if ($search["login_dt_to"] != "")$w[] = "login_dt <= '{$search['login_dt_to']}'";

        if ($search["user_agent"])$w[] = "user_agent like '%{$search[user_agent]}%'";

        return array("total" => $obj->_Size(UserLog, $w),
            "data" => $obj->UserLog($w, $jq->Order(), $jq->Limit()));
    }
}

?>