<?php
use HL\Weather;

class Dashboard_weather extends App\Page
{

    public function get($start, $end)
    {
        $data=[];
        $pool=new Cache\Adapter\Apcu\ApcuCachePool();
        $item=$pool->getItem("weather");
        if ($item->isHit()) {
            $forecast=$item->get();
        } else {
            $w=new Weather();
            $forecast=$w->forecast();
            $item->set($forecast);
            $item->expiresAfter(60);
            $pool->save($item);
        }

        foreach ($forecast as $d) {
            $c = array();
            $c["textEscape"]=true;
            $c["title"]="<i class='wi wi-yahoo-".$d["code"]."'></i> {$d[low]}°C - {$d[high]}°C";
            $c["start"] = $d["date"];
            $c["backgroundColor"]="transparent";
            $c["textColor"]="#924da3";
            $c["borderColor"]="transparent";
            $c["priority"]=1;
            $data[]=$c;
        }
        return $data;
    }
}
