<?php
use App\Translate;
class Translate_all extends ALT\Page {
    public function post() {
        // delete the removed data
        foreach($_POST["_d"] as $id) {
            $t = new Translate($id);
            $t->delete();
        }

        foreach($_POST["_u"] as $id => $c) {
            $t = new Translate($id);
            if ($c["name"] == "")continue;
            foreach(App::Language() as $v => $l) {
                $w = [];
                $w[] = "name='$t->name'";
                $w[] = $t->module?"module='$t->module'":"module is null";
                $w[] = "language='$v'";
                $tt = Translate::first($w);
                $tt->value = $c["value_" . $v];
                $tt->save();
            }
        }

        foreach($_POST["_c"] as $c) {
            if ($c["name"] == "")continue;
            foreach(App::Language() as $v => $l) {
                $t = new Translate();
                $t->language = $v;
                $t->name = $c["name"];
                $t->action = $c["action"];
                $t->value = $c["value_" . $v];
                $t->module = $_POST["module"];
                $t->save();
            }
        }

        App::Redirect("Translate");
    }

    public function get($module = null) {
        // $this->write($this->header());
        // $tpl = $this->template();
        // $tpl->assign("module_select", PSelect2::_("module")->addClass("form-control")->onChange('window.self.location="Translate/all?module="+this.value')->ds(Module::AllModule(), "name", "name")->val($_GET["module"])->mustSelect(false)->name("module"));
        $select = new My\HTML\Select();
        $select->attr("index","module");
        
        $select->attr("name","module");
        $select->ds(App::Module(), "name", "name");
        $select->prepend("<option></option>");
        $select->val($module);
        $select->onChange('window.self.location="Translate/all?module="+this.value');
        $ss[] = (string)$select;

        if ($module) {
            $w[] = ["module=?", $module];
        } else {
            $w[] = "module is null";
        }

        $lang = array_keys(App::Language())[0];

        $w[] = ["language=?", $lang];

        $ts = [];
        foreach(Translate::find($w) as $t) {
            $ts[$t->translate_id]["module"] = $t->module;
            $ts[$t->translate_id]["action"] = $t->action;
            $ts[$t->translate_id]["name"] = $t->name;
            $ts[$t->translate_id]["translate_id"] = $t->translate_id;

            $tran = new Translate($t->translate_id);
            foreach(App::Language() as $lang => $ll) {
                $ts[$t->translate_id]["value_$lang"] = (string)$tran->get("$lang");
            }
        }

        $myt = new App\UI\T($ts, $this->route);

        $myt->addClass('form-create');

        $myt->row()->attr("index", function($o) {
                return $o["translate_id"];
            }
            );

        $myt->add("Action")->input("action");
        $myt->add("Name")->input("name");
        foreach(App::Language() as $v => $l) {
            $myt->add($l)->input("value_$v");
        }

        $ss[] = $myt;
        $this->write($this->createForm(implode("", $ss)));
    }
}

?>
