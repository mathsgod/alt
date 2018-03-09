<?php
class Module_alter_column extends ALT\Page
{

    public function post($table, $field)
    {
        $col=App::DB()->table($table)->columns($field);
        $col->rename($_POST["Field"]);
        App::Msg("Column field changed");
        App::Redirect();
    }

    public function get($table, $field)
    {

        $col=App::DB()->table($table)->columns($field);

        //outp($col);

        $e=$this->createE($col);

        $e->add("Field")->input("Field");

        $this->write($this->createForm($e));
    }
}
