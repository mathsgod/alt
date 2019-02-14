<?php
namespace App;

class Entity
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

    public function User($user_id = null)
    {
        if ($user_id) {
            return new User($user_id);
        }
        return User::Query();
    }

    public function UserGroup($usergroup_id = null)
    {
        if ($usergroup_id) {
            return new UserGroup($usergroup_id);
        }
        return UserGroup::Query();
    }

    public function Config()
    {
        return Config::Query();
    }

}
    