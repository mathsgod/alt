<?php
namespace App;

class User extends Model
{
    public static $_Status = ["Active", "Inactive"];

    public function __construct($id = null)
    {
        parent::__construct($id);
        if (!$id) {
            $this->join_date = date("Y-m-d");
        }
    }

    public static function Login($username, $password, $code = null)
    {
        $sth = self::__db()->prepare("select user_id,password from User where username=:username and status=0");
        $sth->execute([":username" => $username]);
        $row = $sth->fetch();
        $sth->closeCursor();
        if (is_null($row)) {
            return false;
        }

        if (Util::Encrypt($password, $row["password"]) != $row["password"]) {
            return false;
        }

        $user = new User($row["user_id"]);
        if ($user->expiry_date && strtotime($user->expiry_date) < time()) {
            return false;
        }


        return $user;
    }

    public static function _($username)
    {
        return self::Query(["username" => $username])->first();
    }

    public function skin()
    {
        $skin = $this->skin ? $this->skin : self::$_app->config["user"]["default-skin"];

        if (file_exists("themes/$skin")) {
            return new \ALT\Skin("themes/$skin");
        }

        return $skin;
    }

    public function canRead()
    {
        $user = self::$_app->user;
        if ($user->is("Users") && $this->user_id == $user->user_id) {
            return true;
        }
        return parent::canRead();
    }

    public function onlineTime()
    {
        return $this->last_online;
    }

    public function online()
    {
        $this->update(["last_online" => date("Y-m-d H:i:s")]);
    }

    public function offline()
    {
    }

    public function setting()
    {
        if ($setting = $this->setting) {
            return json_decode($setting, true);
        }
        return [];
    }

    private static $_CACHE_USERGROUP = [];
    public function UserGroup()
    {
        if (self::$_CACHE_USERGROUP[$this->user_id]) return self::$_CACHE_USERGROUP[$this->user_id];
        $w[] = ["usergroup_id in (select usergroup_id from UserList where user_id=?)", $this->user_id];
        self::$_CACHE_USERGROUP[$this->user_id] = UserGroup::Find($w);
        return self::$_CACHE_USERGROUP[$this->user_id];
    }

    public function canUpdate()
    {
        $user = self::$_app->user;
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
        $user = self::$_app->user;
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
        $time = strtotime($this->last_online);

        if (time() - $time > 300) {
            return false;
        }
        return true;
    }

    private static $_is = [];
    public function is($name)
    {


        if ($name instanceof UserGroup) {
            $group = $group;
        } else {
            $group = UserGroup::_($name);
        }

        if (isset(self::$_is[$this->user_id])) {
            return in_array($group->usergroup_id, self::$_is[$this->user_id]);
        }

        self::$_is[$this->user_id] = [];
        //get all about user group 
        //self::$_app->log("User::is db", ["name" => $name, "user_id" => $this->user_id]);
        foreach ($this->UserList as $ul) {
            self::$_is[$this->user_id][] = $ul->usergroup_id;
        }
        //self::$_app->log("is data", self::$_is);
        return in_array($group->usergroup_id, self::$_is[$this->user_id]);
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
                return $this->UserList->where(["usergroup_id" => $g->usergroup_id])->count() > 0;
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
        return parent::save();
    }

    public function createUserLog($result = null)
    {
        $r["user_id"] = $this->user_id;
        $r["login_dt"] = date("Y-m-d H:i:s");
        $r["ip"] = $_SERVER['REMOTE_ADDR'];
        $r["result"] = $result;
        $r["user_agent"] = $_SERVER['HTTP_USER_AGENT'];
        UserLog::_table()->insert($r);
    }

    public function Logout()
    {
        $o = UserLog::first("user_id=$this->user_id", "userlog_id desc");
        if ($o) {
            $o->logout_dt = date("Y-m-d H:i:s");
            $o->save();
        }
    }

    public function changePassword($password)
    {
        $this->update(["password" => Util::Encrypt($password)]);
        return true;
    }

    public function sendPassword()
    {
        $password = Util::GeneratePassword();
        $e_pwd = Util::Encrypt($password);

        $ret = $this->update(["password" => $e_pwd]);

        $config = $this->_app()->config;
        $content = $config["user"]["forget pwd email/content"];
        $content = str_replace("{username}", $this->username, $content);
        $content = str_replace("{password}", $password, $content);

        // Send Mail
        $mm = new \App\Mail(true);
        $mm->Subject = $config["user"]["forget pwd email/subject"];
        $mm->msgHTML($content);
        $mm->setFrom("admin@" . $config["user"]["domain"]);
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

    public function removeFrom(UserGroup $usergroup)
    {

        $this->UserList->where([
            "usergroup_id" => $usergroup->usergroup_id
        ])->delete();
    }

    public function addTo(UserGroup $usergroup)
    {
        $w[] = ["user_id=?", [$this->user_id]];
        $w[] = ["usergroup_id=?", [$usergroup->usergroup_id]];
        if (UserList::count($w)) {
            throw new Exception("User already existed in group");
        }
        $ul = new UserList();
        $ul->user_id = $this->user_id;
        $ul->usergroup_id = $usergroup->usergroup_id;
        $ul->save();
    }

    public function __get($name)
    {
        if ($name == "usergroup") {

            $w[] = ["usergroup_id in (select usergroup_id from UserList where user_id=?)", $this->user_id];
            return UserGroup::Query()->where($w);
        }
        return parent::__get($name);
    }
}
