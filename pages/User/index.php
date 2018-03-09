<?php
use App\User;
class User_index extends ALT\Page {

    public function get() {
        $tab = $this->createTab();

        foreach(User::$_Status as $k => $v) {
            $tab->add($v, "list", $k);
        }
        //$tab->add("All user", "list", - 1)->addBadge("a")->addClass("bg-yellow");
        $tab->add("All user", "list", - 1);//->addClass("bg-yellow");
        
        //$tab->add("Test","list2");
        $this->write($tab);
    }

}
