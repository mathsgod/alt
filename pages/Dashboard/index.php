<?php
use App\User;

class Dashboard_index extends ALT\Page {
    public function getUserOnlineBox() {
        $users = User::find()->filter(function($o) {
                return $o->isOnline();
            }
            );

    
        $ul = p("ul")->addClass("users-list clearfix");
        foreach($users as $user) {
        	$t=$user->onlineTime();
            $li = p("li")->appendTo($ul);
            $li->append(<<<EOT
<img src="User/{$user->user_id}/image" alt="User Image" style="max-width:100px;max-height:100px;">
<a class="users-list-name" href="javascript:void(0)">{$user}</a>
<span class="users-list-date">$t</span>
EOT
                );
        }

        $b = $this->createBox();
        $b->header("Online user");
        $b->body()->append($ul);

        return $b;
    }

    public function get() {

        $this->addLib("fullcalendar/fullcalendar");

    	$this->write($this->getUserOnlineBox());
    }
}
