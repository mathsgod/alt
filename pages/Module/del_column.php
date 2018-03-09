<?php
class Module_del_column extends App\Page
{

    public function get($table, $field)
    {
        $ret=App::DB()->table($table)->dropColumn($field);
        App::Msg($ret);
        App::Redirect();
    }
}
