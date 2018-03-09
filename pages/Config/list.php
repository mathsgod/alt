<?php
use App\Config;

class Config_list extends App\Page
{
    public function get()
    {
        foreach (Config::All() as $category => $config) {
            $c = [];
            foreach ($config as $k => $v) {
                $c[] = [$k, $v];
            }

            $t = new App\UI\T($c);
         
            $t->add("Name", function ($o) {
                    return $o[0];
            });

            if ($category=="user") {
                $t->add("Value", function ($o) {
                    $x=new Xeditable\Text();
                    $x->pk=$o[0];
                    $x->url="Config/update";
                    p($x)->text($o[1]);
                    return $x;
                });
            } else {
                $t->add("Value", function ($o) {
                    return $o[1];
                });
            }

            $b = new ALT\Box($this);
            $b->header($category);
            $b->collapsible();

            $b->body()->append($t);
            $this->write($b);
        }
    }
}
