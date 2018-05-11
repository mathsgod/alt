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

        $rt = $this->createRT([$this, "ds"]);
        
        //$rt->attr("page-number",2);

        $rt->attr("data-pagination", ["top", "bottom"]);
    	//$rt->attr("data-mode","virtual");

        $rt->order("eventlog_id", "desc");
        $rt->addView();
    	//$rt->addDel();
        $rt->add("ID", "eventlog_id")->sort()->searchRange()->aLink("v");
        $rt->add("Class", "class")->ss();
        $rt->add("Object ID", "id")->index("id")->ss();
        $rt->add("Action", "action")->ss();
        $rt->add("User", "User()")->index("user_id")->aLink("v")->searchOption(User::find());
        $rt->add("Created time", "created_time")->sort()->searchDate();

        $this->write($rt);
    }

    public function ds($rt)
    {
        $w = $rt->where();
        return array(
            "total" => EventLog::Count($w),
            "data" => EventLog::find($w, $rt->Order(), $rt->Limit())
        );
    }
}