<?php
// Created By: Raymond Chong
// Created Date: 19/3/2010
// Last Updated: 09/05/2012
class EventLog_v extends ALT\Page {
    public function get() {
        $obj = $this->object();

        if ($obj->action == "Delete") {
            $p = $this->header();
            $class = $obj->class;
            try {
                $o = new $class($obj->id);
                if (!$o->id())
                    $p->addButton("Restore", $obj->uri('restore'))->addClass('confirm');
            }
            catch(Exception $e) {
            }
            $this->write($p);
        }

        $mv = $this->createV();
        $mv->header("Details");
        $mv->add("Event Log ID", "eventlog_id");
        $mv->add("Action", "action");
        $mv->add("Class", "class");
        $mv->add("ID", "id");
        $mv->add("User", "User()")->alink("v");
        $mv->add("Source", "source")->attr("data-format",'json');
        $mv->add("Target", 'target')->attr("data-format",'json');
        $mv->add("Created time", "created_time");
        $mv->add("Different", function($obj) {
                $str = array();
                $target = json_decode($obj->target, true);
                foreach($obj->different() as $n => $v) {
                    $str[] = "<dt>{$n}</dt> <dd>{$v} => " . $target[$n] ."</dd>";
                }
                return "<dl>".implode("",$str)."</dl>";
            }
            );
        $mv->add("Remark", "remark")->css("white-space","pre");

        $this->write($mv);
    }
}

?>