<?php

class System_holiday extends App\Page
{
    public function get()
    {
        $composer=App::Composer();

        $holiday=new HL\Holiday("zh-hk");
        outP($holiday->getHoliday("2000-01-01", "2016-01-01"));
    }
}
