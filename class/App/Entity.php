<?php
namespace App;

class Entity extends \R\Entity
{
    public function __get($name)
    {
        if ($name == "User") {
            return $this->User();
        } elseif ($name == "UserGroup") {
            return $this->UserGroup();
        }

        return parent::__get($name);
    }

    public function User($user_id)
    {
        if ($user_id) {
            return new User($user_id);
        }
        $q = new Query("App\User");
        $q->select();
        $q->from("User");

        return $q;
    }

    public function UserGroup($usergroup_id)
    {
        if ($usergroup_id) {
            return new UserGroup($usergroup_id);
        }
        $q = new Query("App\UserGroup");
        $q->select();
        $q->from("UserGroup");
        return $q;
    }

    public function Config()
    {
        $q = new Query("App\Config");
        $q->select();
        $q->from("Config");
        return $q;
    }

}
    