<?php
namespace App;
class Message extends Model {
    public static function Send($to, $title, $content, $uri) {
        $msg = new Message();
        $msg->user_id = $to;
        $msg->title = $title;
        $msg->content = $content;
        $msg->uri = $uri;
        $msg->save();
    }

    public function time() {
        $t = time() - strtotime($this->created_time) ;
        if ($t < 60) {
            return $t . " secs";
        }

        if ($t < 3600) {
            return intval(date("i", $t)) . " mins";
        }

        if ($t < 86400) {
            return intval(date("H", $t)) . " hours";
        }

        return floor($t/86400)." days";

    }
}

?>