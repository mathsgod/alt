<?php
// Created By: Raymond Chong
// Created Date: 5/5/2010
// Last Updated:
use App\SystemValue;

class SystemValue_list extends App\Page
{
    public function get()
    {
        $jq = $this->createRT([$this, "ds"]);

        $jq->addEdit();
        $jq->addDel();
        $jq->Order("name", "asc");
        $jq->add("Name", "name")->sort()->search();

        foreach ($this->app->config["language"] as $v => $l) {
            $jq->add($l, function ($obj) use ($v) {
                return nl2br(SystemValue::_($obj->name, $v));
            })->index("value_$l");
        }

        $this->write($jq);
    }
    public function ds($jq)
    {
        $w = $jq->where();
        $w[] = "language='en'";
        return array(
            "total" => SystemValue::Count($w),
            "data" => SystemValue::Find($w, $jq->Order(), $jq->Limit())
        );
    }
}