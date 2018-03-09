<?php
/**
 * Created By: Raymond Chong
 */
use App\UI;
class UI_save extends App\Page {
    public function saveBox() {
        $uri = App::DB()->quote($_POST["uri"]);

        $w[] = "user_id=" . App::UserID();
        $w[] = "uri={$uri}";
        if (!$ui = UI::first($w)) {
            $ui = new UI();
            $ui->user_id = App::UserID();
            $ui->uri = $_POST["uri"];
        }

        $ui->layout = $_POST["layout"];
        $ui->save();
    }

    public function saveGrid() {
        $uri = App::DB()->quote($_POST["uri"]);

        $w[] = "user_id=" . App::UserID();
        $w[] = "uri={$uri}";
        if (!$ui = UI::first($w)) {
            $ui = new UI();
            $ui->user_id = App::UserID();
            $ui->uri = $_POST["uri"];
        }

        $ui->layout = $_POST["layout"];
        $ui->save();
    }

    public function get() {
        $this->write("UI");
    }

    public function saveFav() {
        $uri = App::DB()->quote($_POST["uri"]);
        $w[] = "user_id=" . App::UserID();
        $w[] = "uri={$uri}";
            $ui = new UI();
            $ui->user_id = App::UserID();
            $ui->uri = $_POST["uri"];

	   	$ui->layout = json_encode($_POST["layout"]);
    	$ui->save();
    }

    public function post() {
        if ($_POST["uri"] == "")die();

        if ($_POST["type"] == "grid") {
            $this->saveGrid();
            return;
        } elseif ($_POST["type"] == "box") {
            $this->saveGrid();
            return;
        } elseif ($_POST["type"] == "fav") {
            $this->saveFav();
            return;
        }

        $uri = App::DB()->quote($_POST["uri"]);
        if ($_POST["RT"]) {
            $ui = UI::_($_POST["uri"]);
            $layout = $ui->layout();
            $layout["visible"] = array();
            foreach($_POST["column"] as $column) {
                $layout["visible"][$column["name"]] = ($column["visible"] == "true");
            }
            $ui->layout = json_encode($layout);
            $ui->save();

            return ;
        }

        if ($_POST["dataTable"]) {
            $w[] = "user_id=" . App::UserID();
            $w[] = "uri={$uri}";
            $o = UI::find($w)->first();
            if (!$o) {
                $o = new UI();
                $o->user_id = App::UserID();
                $o->uri = $_POST["uri"];
            }
            $layout = $o->layout();

            if ($_POST["ColReorder"]) {
                if (!is_array($layout["ColReorder"])) {
                    $layout["ColReorder"] = array();
                }
                $layout["ColReorder"] = $_POST["ColReorder"];
                $o->layout = json_encode($layout);
                $o->save();
            } else {
                if (!is_array($layout["colvis"])) {
                    $layout["colvis"] = array();
                }

                $layout["colvis"][$_POST["column"]] = $_POST["visible"] == "true";
                $o->layout = json_encode($layout);
                $o->save();
            }

            return ;
        }

        $w[] = "user_id=" . App::UserID();
        $w[] = "uri={$uri}";
        $o = UI::find($w)->first();
        if (!$o) {
            $o = new UI();
            $o->user_id = App::UserID();
            $o->uri = $_POST["uri"];
        }
        // clear
        $layout = $o->Layout();
        foreach(json_decode($_POST["data"], true) as $sequence => $value) {
            $name = $value["index"];
            $layout[$name]["show"] = (bool)$value["checked"];
            $layout[$name]["sequence"] = $sequence;
        }
        $o->layout = json_encode($layout);

        $o->Save();
    }

    public function reset($uri) {
        if (!$uri)die();
        $uri = App::db()->quote($uri);
        App::User()->_delete("UI", "uri=$uri");
        return;
    }
}

?>