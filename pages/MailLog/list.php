<?php
/*
Created by: Raymond Chong
Created time: 27/6/2016 16:13:35
*/

class MailLog_list extends App\Page
{
    public function getRT2()
    {
        $rt = $this->createRT2([$this, "ds"]);


        // $rt->key("maillog_id");
        $rt->order("maillog_id", "desc");



        $rt->addSubRow("content");

        $rt->add("ID","maillog_id");
        $rt->add("Subject", "subject");

        $rt->add("From", "from");
        $rt->add("To", "to");

        $rt->add("Created time", "created_time");
        return $rt;
    }

    public function get()
    {

        return $this->write($this->getRT2());
    }

    public function content($maillog_id)
    {
        $o = new App\MailLog($maillog_id);
        $src = $o->uri("body");

        $this->write("<iframe width='100%' src='$src'></iframe>");
    }

    public function ds($rt)
    {

        if (!$this->getRT2()->validate($rt)) {
            throw new Exception("access deny");
        }
        $rt->source = App\MailLog::Query();

        $rt->addSubRow("content", [$this, "content"], "maillog_id");

        return $rt;
        $w = $r->where();

        return [
            "total" => App\MailLog::Count($w),
            "data" => App\MailLog::Find($w, $r->order(), $r->limit())
        ];
    }
}
