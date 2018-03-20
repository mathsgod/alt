<?php
// Created By: Raymond Chong
// Created Date: 19/2/2010
// Last Updated: 2013-04-12
use App\EventLog;
use App\User;
class EventLog_list extends App\Page {
    public function get() {
        $jq = $this->createRT([$this, "Datasource"]);
        //$jq->attr("page-number",2);

    	$jq->attr("data-pagination",["top","bottom"]);
    	//$jq->attr("data-mode","virtual");

        $jq->order("eventlog_id", "desc");
    	//$jq->addDel();
        $jq->add("ID", "eventlog_id")->sort()->searchRange()->aLink("v");
        $jq->add("Class", "class")->ss();
        $jq->add("Object ID", "id")->index("id")->ss();
        $jq->add("Action", "action")->ss();
        $jq->add("User", "User()")->index("user_id")->aLink("v")->searchOption(User::find());
        $jq->add("Created time", "created_time")->sort()->searchDate();

        $this->write($jq);
    }

    public function DataSource($jq) {


        $w = $jq->where();
        return array("total" => EventLog::Count($w),
            "data" => EventLog::find($w, $jq->Order(), $jq->Limit()));
    }
}