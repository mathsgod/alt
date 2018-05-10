<?php

class System_alter_table_utf8 extends ALT\Page
{

    public function post()
    {
        $charset = $_POST["charset"];

        foreach (App::DB()->query("SHOW CHARACTER SET")->fetchAll() as $c) {
            if ($c["Charset"] == $charset) {
                $collation = $c["Default collation"];
                break;
            }
        }

        foreach (App::DB()->query("show tables") as $table) {
            foreach ($table as $t) {
                try {
                    App::Msg("ALTER TABLE  `$t` DEFAULT CHARACTER SET {$charset} COLLATE {$collation};");
                    App::db()->exec("ALTER TABLE  `$t` DEFAULT CHARACTER SET {$charset} COLLATE {$collation};");
                } catch (Exception $e) {
                    App::Msg($e->getMessage(), "error");
                }
            }
        }
        App::Redirect();
    }

    public function get()
    {

        $e = $this->createE();

        $charset = array_map(function ($o) {
            return $o["Charset"];
        }, $this->app->db->query("SHOW CHARACTER SET")->fetchAll());

        sort($charset);

        $s=$e->add("Charset")->select("charset")->options($charset);


        $this->write($this->createForm($e));
    }
}
