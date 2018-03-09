<?php

class User_myfav extends ALT\Page
{
    public function post()
    {


        foreach ($_POST["ui_id"] as $i => $ui_id) {
            $ui = new App\UI($ui_id);
            $content = $ui->content();
            $content["sequence"] = $i;
            $ui->layout = json_encode($content, JSON_UNESCAPED_UNICODE);
            $ui->save();
        }


    }

    public function get()
    {
        App::SavePlace();
        $w[] = "user_id=" . App::UserID();
        $w[] = "uri='fav'";
        $ds = App\UI::find($w)->usort(function ($a, $b) {
            if ($a->content()["sequence"] > $b->content()["sequence"]) {
                return 1;
            } elseif ($a->content()["sequence"] < $b->content()["sequence"]) {
                return -1;
            }
            return 0;
        });


        $t = $this->createT($ds);
        $t->attr("id", "table1");
        $t->header("My favorite");
        $t->add("", function ($o) {
            return "<i class='fa fa-sort'></i><input type='hidden' name='ui_id[]' value='{$o->ui_id}'/>";
        });

        $t->addEdit();
        $t->addDel();
        $t->add("Label", function ($ui) {
            return $ui->content()["label"];
        });
        $t->add("Link", function ($ui) {
            return $ui->content()["link"];
        });

        $t->add("Icon", function ($ui) {
            $icon = $ui->content()["icon"];
            $i = p("i")->addClass("fa")->addClass($icon);
            $color = $ui->content()["color"];
            if ($color) {
                $i->addClass($color);
            }
            return $i;
        });
	// $t->add("Link", "link");
        $f = p("form")->attr("id", "form1")->attr("method", "post")->append($t);
        $this->write($f);
    }
}

?>

<script>
$(function(){
	$("#table1 table>tbody").sortable({
		handle:".fa-sort",
		axis: "y",
		cursor: "move",
		update:function(event,ui){
			$("#form1").ajaxSubmit();
		}
	});
	
/*	$("#table1 table").tableDnD({
		onDragStop:function(table,row){
		
		}
		
	});*/
});
</script>