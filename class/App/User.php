<?php
namespace App;

class User extends Model
{
    public static $_Status = ["Active", "Inactive"];

    public function __construct($id)
    {
        parent::__construct($id);
        if (!$id) {
            $this->join_date = date("Y-m-d");
        }
    }

    public static function _($username)
    {
        return self::first([["username=?", $username]]);
    }

    public function skin()
    {
        $skin = $this->skin ? $this->skin : (string)Config::_("default-skin");

        if (file_exists("themes/$skin")) {
            return new \ALT\Skin("themes/$skin");
        }

        return $skin;
    }

    public function canRead()
    {
        if (\App::User()->is("Users") && $this->user_id == \App::UserID()) {
            return true;
        }
        return parent::canRead();
    }

    public function onlineTime()
    {

        if (\PHP\APCu::Exists()) {
            $o = new \PHP\APCu();
        } else {
            $o = new \PHP\SHM();
        }

        if (!$r = $o->read()) {
            $r = [];
        }

        return date("Y-m-d H:i:s", $r["online"][$this->user_id]);
    }

    public function online()
    {
        if (\PHP\APCu::Exists()) {
            $o = new \PHP\APCu();
        } elseif (\PHP\SHM::Exists()) {
            $o = new \PHP\SHM();
        } else {
            return;
        }
        if (!is_array($r)) {
            $r = [];
        }
        $r["online"][$this->user_id] = time();
        $o->write($r);
    }

    public function offline()
    {
        if (\PHP\APCu::Exists()) {
            $o = new \PHP\APCu();
        } elseif (\PHP\SHM::Exists()) {
            $o = new \PHP\SHM();
        } else {
            return;
        }
        unset($r["online"][$this->user_id]);

        $o->write($r);
    }

    public function setting()
    {
        if ($setting = $this->setting) {
            return json_decode($setting, true);
        }
        return [];

    }

    public function UserGroup()
    {
        $w[] = ["usergroup_id in (select usergroup_id from UserList where user_id=?)", $this->user_id];
        return UserGroup::Find($w);
    }

    public function canUpdate()
    {
        $user = \App::User();
        if ($this->isGuest()) {
            return false;
        }
        if ($user->user_id == $this->user_id) {
            return true;
        }
        if ($user->isAdmin()) {
            return parent::canUpdate();
        }
        if ($user->isPowerUser()) {
            if ($this->isAdmin()) {
                return false;
            }
        }
        return parent::canUpdate();
    }

    public function canDelete()
    {
        $user = \App::User();
        if ($this->isGuest()) {
            return false;
        }
        if ($user->user_id == $this->user_id) {
            return false;
        }
        if ($user->isAdmin()) {
            return parent::canDelete();
        }
        if ($user->isPowerUser()) {
            if ($this->isAdmin()) {
                return false;
            }
        }

        return parent::canDelete();
    }

    public function __tostring()
    {
        return $this->first_name . " " . $this->last_name;
    }

    public function isAdmin()
    {
        return $this->is("Administrators");
    }

    public function isPowerUser()
    {
        return $this->is("Power Users");
    }

    public function isUser()
    {
        return $this->is("Users");
    }

    public function isGuest()
    {
        return $this->is("Guests");
    }

    public function isOnline()
    {
        if (\PHP\APCu::Exists()) {
            $o = new \PHP\APCu();
        } elseif (\PHP\SHM::Exists()) {
            $o = new \PHP\SHM();
        } else {
            return false;
        }


        if ($data = $o->read()) {
            $time = $data["online"][$this->user_id];
            if (!$time) {
                return false;
            }
            if (time() - $time > 300) {
                return false;
            }
            return true;
        }

        return false;
    }

    private static $_is = [];
    public function is($name)
    {
        if (is_object($name)) {
            $group = $name;
            self::$_is[$this->user_id][$group->name] = $this->_Size(UserList, "usergroup_id={$group->usergroup_id}");
            return self::$_is[$this->user_id][$group->name];
        }

        if (!is_null(self::$_is[$this->user_id][$name])) {
            return self::$_is[$this->user_id][$name];
        }

        if ($group = UserGroup::_($name)) { // usergroup exitst
            self::$_is[$this->user_id][$name] = $this->_Size(UserList, "usergroup_id={$group->usergroup_id}");
        } else {
            self::$_is[$this->user_id][$name] = 0;
        }
        return self::$_is[$this->user_id][$name];
    }

    public function isOneOf($group)
    {
        if (is_array($group)) {
            foreach ($group as $g) {
                if ($this->isOneOf($g)) {
                    return true;
                }
            }
        } else {
            if ($g = UserGroup::_($group)) {
                if ($this->_size(UserList, "usergroup_id=$g->usergroup_id")) {
                    return true;
                }
            }
        }

        return false;
    }

    public function save()
    {
        if (!$this->user_id) {
            $this->password = Util::Encrypt($this->password);
        } else {
            $o = new User($this->user_id);
            if ($this->password == "" or $this->password == $o->password) {
                $this->password = null;
            } else {
                $this->password = Util::Encrypt($this->password);
            }
        }
        parent::save();
    }

    public function createUserLog($result = null)
    {
        $r["user_id"] = $this->user_id;
        $r["login_dt"] = date("Y-m-d H:i:s");
        $r["ip"] = $_SERVER['REMOTE_ADDR'];
        $r["result"] = $result;
        $r["user_agent"] = $_SERVER['HTTP_USER_AGENT'];
        self::__db()->insert('UserLog', $r);
    }

    public function Logout()
    {
        $o = UserLog::first("user_id=$this->user_id", ["userlog_id", "desc"]);
        if ($o) {
            $dt = date("Y-m-d H:i:s");
            $userlog_id = $o->userlog_id;
            self::__db()->exec("Update UserLog set logout_dt='$dt' where userlog_id=$userlog_id");
        }
    }

    public function sendPassword()
    {
        $password = Util::GeneratePassword();
        $e_pwd = Util::Encrypt($password);
        $this->update(["password" => $e_pwd]);

        $tpl = \App::TPL("email/forget_password.tpl");
        $tpl->assign("username", $this->username);
        $tpl->assign("password", $password);
        // Send Mail
        $mm = new \App\Mail();
        $mm->Subject = \App::Config("user", "domain") . " reset password";
        $mm->msgHTML($tpl->getOutputContent());
        $mm->setFrom("admin@" . \App::Config("user", "domain"));
        $mm->addAddress($this->email);
        $mm->Send();
        return $password;
    }

    public function checkCode($code)
    {
        require_once(SYSTEM . "/plugins/GoogleAuthenticator/GoogleAuthenticator.php");
        $g = new \GoogleAuthenticator();
        return $g->checkCode($this->secret, $code);
    }

    public function removeFrom($usergroup)
    {
        if (!$usergroup->usergroup_id) {
            return;
        }

        $w[] = "user_id='$this->user_id'";
        $w[] = "usergroup_id='$usergroup->usergroup_id'";
        foreach (UserList::find($w) as $ul) {
            $ul->delete();
        }
    }

    public function addTo($usergroup)
    {
        if (!$usergroup->usergroup_id) {
            throw new Exception("Usergroup not found");
        }
        $w[] = "user_id='$this->user_id'";
        $w[] = "usergroup_id='$usergroup->usergroup_id'";
        if (UserList::count($w)) {
            throw new Exception("User already existed in group");
        }
        $ul = new UserList();
        $ul->user_id = $this->user_id;
        $ul->usergroup_id = $usergroup->usergroup_id;
        $ul->save();
    }
}
