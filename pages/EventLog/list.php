<?php
// Created By: Raymond Chong
// Created Date: 19/2/2010
// Last Updated: 2013-04-12
use App\EventLog;
use App\User;

class EventLog_list extends App\Page
{
    public function get()
    {

        $rt = $this->createRT2([$this, "ds"]);

        //$rt->attr("page-number",2);

        //$rt->attr("data-pagination", ["top", "bottom"]);
        //$rt->attr("data-mode","virtual");

        $rt->order("eventlog_id", "desc");
        $rt->addView();
        //$rt->addDel();
        $rt->add("ID", "eventlog_id")->ss();
        $rt->add("Class", "class")->ss();
        $rt->add("Object ID", "id")->ss();
        $rt->add("Action", "action")->ss();
        $rt->add("User", "user_id")->searchOption(User::find());
        $rt->add("Created time", "created_time")->sort()->searchDate();
        //   $rt->add("Target","target")->width("100px");



        $this->write($rt);
    }

    public function ds($rt)
    {
        $rt->source = EventLog::Query();
        $rt->add("user_id", "User()")->alink("v");

        $rt->add("cb", "eventlog_id");


        //$rt->addCheckbox("id");
        return $rt;
    }
}
