<?php
namespace App;

use Cache\Adapter\Apcu\ApcuCachePool;

class Config extends Model
{
    public static function _($name, $value = null)
    {

        if ($value != "") {
            $config = self::All()["user"][$name];
            if (!$config instanceof Model) {
                $config = new Config();
                $config->name = $name;
            }

            $config->value = $value;
            $config->save();
        } else {
            return self::All()["user"][$name];
        }
    }

    public function __toString()
    {
        return $this->value;
    }

    private static $_config = [];
    public static function All()
    {
        if (sizeof(self::$_config)) return self::$_config;
        // system config
        $config = \App::_()->config;

        // user config
        foreach ($config as $cat => $ar) {
            foreach ($ar as $k => $v) {
                if ($cat == "user") {
                    $v = str_replace("{username}", \App::User()->username, $v);
                    $v = str_replace("{language}", \My::Language(), $v);
                }
                $config[$cat][$k] = $v;
            }
        }

        foreach (Config::find() as $c) {
            $config["user"][$c->name] = $c;
        }

        self::$_config = $config;
        return $config;
    }

    public function save($acl)
    {
        $host = $_SERVER["HTTP_HOST"];
        $pool = new ApcuCachePool();
        $pool->deleteItem($host . "_config");

        return parent::save($acl);
    }

    public function delete($acl)
    {
        $host = $_SERVER["HTTP_HOST"];

        $pool = new ApcuCachePool();
        $pool->deleteItem($host . "_config");

        return parent::delete($acl);
    }
}
