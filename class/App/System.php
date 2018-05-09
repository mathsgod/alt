<?php
namespace App;

use R\Psr7\Response;
use R\Psr7\Stream;
use R\Psr7\ServerRequest;

class System
{
    public static $app;
    public static $base;
    public static $request;

    public function version()
    {
        if ($_SESSION["app"]["version"]) return $_SESSION["app"]["version"];
        $composer = new Composer();
        $ret = $composer->lockConfig();
        foreach ($ret["packages"] as $package) {
            if ($package["name"] == "hostlink/r-alt") {
                $_SESSION["app"]["version"] = $package["version"];
                return $package["version"];
            }
        }
        return;
    }

    public static function Config($name, $category)
    {
        $app = self::$app;
        if (func_num_args() == 1) {
            return $app->config[$name];
        }
        if (func_num_args() == 2) {
            return $app->config[$name][$category];
        }
        return $app->config;
    }

    public static function Root()
    {
        return SYSTEM;
    }

    public function log()
    {
        if ($this->logger) {
            return call_user_func_array([$this->logger, "info"], func_get_args());
        }
    }


    public static function Path($uri)
    {
        $f = CMS_ROOT . "/$uri";
        if (is_readable($f)) {
            return $f;
        }
        $f = SYSTEM . "/$uri";
        if (is_readable($f)) {
            return $f;
        }
    }

    public static function Msg($message, $type = "success")
    {
        $_SESSION["app"]["message"][] = [$message, $type];
    }

    public static function FlushMessage()
    {
        $msg = $_SESSION["app"]["message"];
        $_SESSION["app"]["message"] = [];
        return $msg;
    }

    public static function HaveMsg()
    {
        return sizeof($_SESSION["app"]["message"]) > 0;
    }

    public static function Locale()
    {
        $locale = `locale -a`;

        return array_filter(explode("\n", $locale), function ($l) {
            return strlen($l) > 0;
        });
    }

    public static function IP2StepExemptCheck($ip)
    {
        $ips = explode(",", \App::Config("user", "2-step verification white list"));

        foreach ($ips as $i) {
            $cx = explode("/", $i);
            if (sizeof($cx) == 1) {
                $cx[1] = "255.255.255.255";
            }
            $res = ip2long($cx[0]) & ip2long($cx[1]);
            $res2 = ip2long($ip) & ip2long($cx[1]);
            if ($res == $res2) {
                return true;
            }
        }
        return false;
    }


    public static function Language()
    {
        $config = self::$app->config;
        if (!$config["language"]) {
            return ["en" => "English"];
        }
        return $config["language"];
    }

    public function systemModeStart()
    {
        $system = User::_("system");
        $_SESSION["app"]["user_id"] = $system->user_id;

        $_SESSION["app"]["user"] = $system;

        $_SESSION["app"]["login"] = true;
    }

    public function systemModeEnd()
    {
        $_SESSION["app"] = [];
    }
}
