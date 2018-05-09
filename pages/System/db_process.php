<?php
// Created By: Raymond Chong
// Created Date: 2015-09-22
// Last Updated:
class System_db_process extends ALT\Page
{
	public function get()
	{
		$this->write('<div id="content1">Loading...</div>');
	}

	public function data()
	{

		$mv = $this->createT($this->app->db->query("Show Full ProcessList"));
		$mv->add("ID", "Id");
		$mv->add("User", "User");
		$mv->add("Host", "Host");
		$mv->add("DB", "db");
		$mv->add("Command", "Command");
		$mv->add("Time", "Time");
		$mv->add("State", "State");
		$mv->add("Info", "Info");
		$mv->add("Button")->button("Id")->attr("onClick", 'onClickKill(this)')->text("Kill");

		$this->write($mv);
	}

	public function kill($process_id)
	{
		$this->app->db->exec("kill $process_id");
	}
}

?>
<script language="javascript">
function onClickKill(obj){
	var id=$(obj).attr('data');
	$.get("System/db_process/kill?process_id="+id).done(function(){
		load_process();
	});
}

$(function(){
	var f=function(){
		$("#content1").load("System/db_process/data");
	};
	$.timer(5000, function (timer) {
		f();
	});
	f();
});
</script>