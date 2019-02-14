<?php
// Created By: Raymond Chong
// Created Date: 19/3/2010
// Last Updated:
namespace App;

class EventLog extends Model
{
    public function different()
    {
        $source = json_decode($this->source, true);
        $target = json_decode($this->target, true);
        return array_diff_assoc($source, $target);
    }

    public static function LogDelete($object)
    {
        $class = get_class($object);
        if ($class == "App\EventLog") return;

        $r["id"] = $object->id();
        $r["user_id"] = \App::_()->user->user_id;
        $r["class"] = $class;
        $r["action"] = "Delete";
        $r["target"] = json_encode($object);
        $remark = array();
        foreach (get_object_vars($object) as $k => $v) {
            if ($k[0] == "_") continue;
            $remark[] = $k . " => " . $v;
        }
        $r["remark"] = implode(chr(10), $remark);
        $r["created_time"] = date("Y-m-d H:i:s");
        self::_table()->insert($r);
    }

    public static function Log($object, $action)
    {
        $class = get_class($object);
        if ($class == "App\EventLog") return;
        // check the module
        $rc = new \ReflectionClass($class);
        $short_name = $rc->getShortName();
        $m = Module::All()[$short_name];

        if (!$m->log) return;

        $r["user_id"] = \App::User()->user_id;
        $r["class"] = $class;

        $r["id"] = $object->id();
        if ($action == "U") {
            $r["action"] = "Update";
            $c = "\\$class";
            $org = new $c($r["id"]);
            $r["source"] = json_encode($org);
            $r["target"] = json_encode($object);
        } else {
            $r["action"] = "Insert";
            $r["target"] = json_encode($object);
        }
        $r["created_time"] = date("Y-m-d H:i:s");
        self::_table()->insert($r);
    }
}