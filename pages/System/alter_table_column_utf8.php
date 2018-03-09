<?php

class System_alter_table_column_utf8 extends App\Page {
    public function get() {



        foreach(App::DB()->query("show tables") as $table) {
            foreach($table as $t) {
                foreach(App::DB()->query("describe `$t`") as $row) {
                	if(strtolower(substr($row["Type"],0,7))=="varchar" || strtolower($row["Type"])=="text" || strtolower($row["Type"])=="longtext"){
                        App::Msg("ALTER TABLE  `$t` CHANGE COLUMN `{$row[Field]}` `{$row[Field]}` {$row[Type]} NULL DEFAULT NULL;");
                        try{
                            App::db()->exec("ALTER TABLE  `$t` CHANGE COLUMN `{$row[Field]}` `{$row[Field]}` {$row[Type]} NULL DEFAULT NULL;");
                        }catch(Exception $e){
                            App::Msg($e->getMessage(),"error");
                        }
                	}

                }
            }
        }
        App::Redirect();
    }
}