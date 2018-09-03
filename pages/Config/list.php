<?php
use App\Config;

class Config_list extends App\Page
{
    public function get()
    {

        foreach ($this->app->config as $category => $config) {
            $c = [];
            foreach ($config as $k => $v) {
                $c[] = [$k, $v];
            }

            $t = $this->createT($c);

            $t->add("Name", function ($o) {
                return $o[0];
            });

            if ($category == "user") {
                $t->add("Value", function ($o) {
                    $x = new Xeditable\Text();
                    $x->pk = $o[0];
                    $x->url = "Config/update";
                    p($x)->text($o[1]);
                    return $x;
                });
            } else {
                $t->add("Value", function ($o) {
                    return $o[1];
                });
            }
            $t->header($category);
            $this->write($t);

        }
    }
}
