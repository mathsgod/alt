<?
class System_db_table extends ALT\Page
{
    public function get()
    {
        $t=$this->createT($this->app->db->tables);

        $t->table->addClass("DataTable");
        //$t->table->attr("data-searching",true);

        $t->add("Name",function($o){
            return html("a")->href("System/db/v_table?table=".$o->name)->text($o->name);
        });

        $this->write($t);

    }
}