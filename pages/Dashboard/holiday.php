<?php
use HL\Holiday;

class Dashboard_holiday extends App\Page
{
    public function get($start, $end)
    {

        $h=new HL\Holiday(My::Language());

        foreach ($h->getHoliday($start, $end) as $d) {
            $c = array();
            $c["title"] = $d["name"];
            $c["start"] = $d["date"];
            $c["color"]="red";
    
            $holiday[] = $c;
        }
        return $holiday;
    }
}
