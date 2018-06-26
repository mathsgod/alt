<?php
// Created By: Raymond Chong
// Created Date: 22/2/2010
// Last Updated: 2013-09-12
use App\ACL;
use App\Module;
use App\UserGroup;
use App\User;

class ACL_ae_module extends ALT\Page
{
    public function post($module)
    {
        $user_id = $_POST["user_id"];
        $usergroup_id = $_POST["usergroup_id"];
        $special_user = $_POST["special_user"];

        if ($_POST["regexp"]) {
            $v = array("allow", "deny");
            $o = new ACL();
            $o->module = $module;
            $o->path = $_POST["regexp"];
            $o->user_id = $user_id;
            $o->usergroup_id = $usergroup_id;
            $o->special_user = $special_user;
            $o->value = $v[$_POST["regexp_value"]];
            $o->type = 1;
            $o->save();
        }

        $w = array();
        $w[] = "module='$module'";
        $w[] = "not action is null";
        if ($user_id) {
            $w[] = "user_id=$user_id";
        } elseif ($usergroup_id) {
            $w[] = "usergroup_id=$usergroup_id";
        } else {
            $w[] = "special_user=$special_user";
        }
        foreach (ACL::find($w) as $acl) {
            $acl->delete();
        }

        foreach ($_POST["allow"] as $action => $value) {
            $o = new ACL();
            $o->module = $module;
            $o->action = $action;
            $o->user_id = $user_id;
            $o->usergroup_id = $usergroup_id;
            $o->special_user = $special_user;
            $o->value = "allow";
            $o->save();
        }
        foreach ($_POST["deny"] as $action => $value) {
            $o = new ACL();
            $o->module = $module;
            $o->action = $action;
            $o->user_id = $user_id;
            $o->special_user = $special_user;
            $o->usergroup_id = $usergroup_id;
            $o->value = "deny";
            $o->save();
        }
        // --------
        $paths = array("");
        foreach (Module::_($module)->getAction() as $act) {
            $paths[] = $act["filename"];
        }

        foreach ($paths as $path) {
            $w = array();
            $w[] = "module='$module'";
            $w[] = "path='{$path}'";
            $w[] = "action is null";
            $w[] = "type=0";
            if ($user_id) {
                $w[] = "user_id=$user_id";
            } elseif ($usergroup_id) {
                $w[] = "usergroup_id=$usergroup_id";
            } else {
                $w[] = "special_user=$special_user";
            }

            foreach (ACL::find($w) as $acl) {
                $acl->delete();
            }
        }

        foreach ($_POST["allow_path"] as $path) {
            $r = array();
            $r["module"] = $module;
            $r["path"] = $path;
            if ($user_id) $r["user_id"] = $user_id;
            if ($usergroup_id) $r["usergroup_id"] = $usergroup_id;
            if ($special_user) $r["special_user"] = $special_user;
            $r["value"] = "allow";
            $this->app->db->insert(ACL, $r)->execute();
        }

        foreach ($_POST["deny_path"] as $path) {
            $r = array();
            $r["module"] = $module;
            $r["path"] = $path;
            if ($user_id) $r["user_id"] = $user_id;
            if ($usergroup_id) $r["usergroup_id"] = $usergroup_id;
            if ($special_user) $r["special_user"] = $special_user;
            $r["value"] = "deny";
            $this->app->db->insert(ACL, $r)->execute();
        }
        $this->alert->success("ACL updated");
        $this->_redirect();
    }

    public function get()
    {
        $this->header("Module ACL");

        $mv = $this->createE(null);
        $ms = $this->app->getModule();
        usort($ms, function ($a, $b) {
            return $a->class > $b->class;
        });

        $mv->add("Module")->select("module")->ds($ms, "class", "name")->attr("onChange", "onChangeModule(this.value)")->prepend("<option/>")->val($_GET["module"]);
        $this->write($mv);
        $module = $_GET["module"];

        $module = Module::_($module);
        if (!$module) {
            return;
        }

        $w[] = "module = " . $this->app->db->quote($_GET["module"]);

        $mt = $this->createT(ACL::find($w));
        $mt->addDel();
        $mt->add("User / User Group", "UserName()");
        $mt->add("Path", "path()");
        $mt->add("Action", "action");
        $mt->add("Value", "value");
        $mt->add("Type", "Type()");
        $this->write($mt);

        $this->write("<hr/>");

        $f = $this->createForm();

        $mv = My::E();
        $mv->add("UserGroup")->select("usergroup_id")->ds(UserGroup::find(), "name", "usergroup_id")->attr("onChange", "onChangeUserGroup(this.value)")->prepend("<option/>")->val($_GET["usergroup_id"]);
        $mv->add("User")->select("user_id")->ds(User::find(), function ($o) {
            return (string)$o . " ($o->username)";
        }, "user_id")->attr("onChange", "onChangeUser(this.value)")->prepend("<option/>")->val($_GET["user_id"]);
        $mv->add("Special User")->select("special_user")->ds(ACL::$_SPECIAL_USER)->attr("onChange", "onChangeSpecialUser(this.value)")->prepend("<option/>")->val($_GET["special_user"]);

        if (!$_GET["user_id"] && !$_GET["usergroup_id"] && !$_GET["special_user"]) {
            $this->write(My::Box($mv));

            return;
        }

        $f->addBody($mv);

        $user_id = $_GET["user_id"];
        $usergroup_id = $_GET["usergroup_id"];
        $special_user = $_GET["special_user"];

        $mt = My::T(ACL::$_ACTION);
        $mt->add("Action", function ($obj) {
            return $obj;
        });

        $mt->add("Allow", function ($action) use ($module, $user_id, $usergroup_id, $special_user) {
            $c = p("input")->attr("type", "checkbox")->attr("name", "allow[$action]")->addClass("allow");
            $w[] = "module=" . $this->app->db->quote($module->class);
            $w[] = "action='$action'";
            $w[] = "value='allow'";
            if ($user_id) {
                $w[] = "user_id=$user_id";
            } elseif ($usergroup_id) {
                $w[] = "usergroup_id=$usergroup_id";
            } elseif ($special_user) {
                $w[] = "special_user=$special_user";
            }
            if (ACL::count($w)) {
                $c->attr("checked", true);
            }
            $c->attr("onChange", "onChangeAllow(this)");
            return (string)$c;
        });

        $mt->add("Deny", function ($action) use ($module, $user_id, $usergroup_id, $special_user) {
            $c = p("input")->attr("type", "checkbox")->attr("name", "deny[$action]")->addClass("deny");
            $w[] = "module=" . $this->app->db->quote($module->class);
            $w[] = "action='$action'";
            $w[] = "value='deny'";
            if ($user_id) {
                $w[] = "user_id=$user_id";
            } elseif ($usergroup_id) {
                $w[] = "usergroup_id=$usergroup_id";
            } elseif ($special_user) {
                $w[] = "special_user=$special_user";
            }
            if (ACL::count($w)) {
                $c->attr("checked", true);
            }
            $c->attr("onChange", "onChangeDeny(this)");
            return (string)$c;
        });

        $f->addBody($mt);
        // ----------------------------------------------------------------------
        $paths = array("");
        foreach ($module->getAction() as $act) {
            $paths[] = $act["filename"];
        }

        $mt = My::T($paths);
        $mt->add("Path", function ($obj) use ($module) {
            if ($obj == "") return "[$module->name]";
            return $obj;
        });
        $mt->add("Allow", function ($path) use ($module, $user_id, $usergroup_id, $special_user) {
            $c = p("input")->attr("type", "checkbox")->attr("name", "allow_path[]")->addClass("allow")->val($path);
            $w[] = "module='$module->name'";
            $w[] = "path='{$path}'";
            $w[] = "value='allow'";
            if ($user_id) {
                $w[] = "user_id=" . $user_id;
            } elseif ($usergroup_id) {
                $w[] = "usergroup_id=$usergroup_id";
            } elseif ($special_user) {
                $w[] = "special_user=$special_user";
            }
            if (ACL::count($w)) {
                $c->attr("checked", true);
            }
            $c->attr("onChange", "onChangeAllow(this)");
            return (string)$c;
        });
        $mt->add("Deny", function ($path) use ($module, $user_id, $usergroup_id, $special_user) {
            $c = p("input")->attr("type", "checkbox")->attr("name", "deny_path[]")->addClass('deny')->val($path);
            $w[] = "module='$module->name'";
            $w[] = "path='{$path}'";
            $w[] = "value='deny'";
            if ($user_id) {
                $w[] = "user_id=" . $user_id;
            } elseif ($usergroup_id) {
                $w[] = "usergroup_id=$usergroup_id";
            } elseif ($special_user) {
                $w[] = "special_user=$special_user";
            }
            if (ACL::count($w)) {
                $c->attr("checked", true);
            }
            $c->attr("onChange", "onChangeDeny(this)");
            return (string)$c;
        });

        $f->addBody($mt);

        $f->addBody("<hr/>");

        $mv = My::E();
        $mv->add("Regexp")->input("regexp");
        $mv->add("Value")->select("regexp_value")->ds(array("allow", "deny"));
        $f->addBody((string)$mv);

        $f->addHidden("module", $_GET["module"]);

        $this->write($f);
    }
}

?>
<script language="javascript">

function onChangeAllow(checkbox){
	if($(checkbox).is(":checked")){
		$(checkbox).closest("tr").find(".deny").prop("checked",false);
	}
}

function onChangeDeny(checkbox){
	if($(checkbox).is(":checked")){
		$(checkbox).closest("tr").find(".allow").prop("checked",false);
	}
}

function onChangeModule(module){
	window.self.location='ACL/ae_module?module='+module;
}

function onChangeUserGroup(id){
	window.self.location='ACL/ae_module?module='+purl().param("module")+'&usergroup_id='+id;
}
function onChangeUser(id){
	window.self.location='ACL/ae_module?module='+purl().param("module")+'&user_id='+id;
}

function onChangeSpecialUser(id){
	window.self.location='ACL/ae_module?module='+purl().param("module")+'&special_user='+id;
}
</script>
