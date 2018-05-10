<?php
class Module_del_column extends App\Page
{

    public function get($table, $field)
    {
        $ret=$this->app->db->table($table)->dropColumn($field);
        $this->app->alert->info($ret);
        $this->redirect();
    }
}
