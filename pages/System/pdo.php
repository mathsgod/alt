<?php

class System_pdo extends ALT\Page {
    public function clear() {
        App::DB()->query("Delete from pdo_log");
        App::Msg("pdo_log cleared");
        App::Redirect("System/pdo");
    }

    public function get() {
        $h = $this->navBar();
        $h->addButton("Clear pdo", "System/pdo/clear");

        $mt = $this->createT(App::DB()->query("select * from pdo_log"));
        $mt->add("SQL", "sql");
        $mt->add("Date", function($obj) {
                return $obj["date"];
            }
            );

        $this->write($mt);
    }
}

?>