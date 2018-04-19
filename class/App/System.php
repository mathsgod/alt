<?php
namespace App;

use R\Psr7\Response;
use R\Psr7\Stream;
use R\Psr7\ServerRequest;

class System extends \R\System
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

  

    public static function Run($root, $composer, $logger)
    {
        if ($logger) $logger->info("System::Run");
        self::Init($root, $composer, $logger);

        session_start();
        $request = ServerRequest::FromEnv();
        self::$request = $request;
        self::$base = $request->getUri()->getBasePath();

        $b = self::$app->pathInfo();

        $router = new Router();
        $router->add("GET", "404_not_found", [
            "class" => "_404_not_found",
            "file" => $b["system_root"] . "/pages/404_not_found.php"
        ]);

        ob_start();
        $route = $router->getRoute($request, $composer);
        $request = $request->withAttribute("included_content", ob_get_contents());
        ob_end_clean();


        $request = $request
            ->withAttribute("action", $route->action)
            ->withAttribute("route", $route);

        if (!$page) {
            if (!$class = $route->class) {
                if (\App::User()->isAdmin()) {
                    $page = new ClassNotExistPage();
                }
            } else {
                $class = $route->class;
                $page = new $class();
            }
        }


        if ($page) {
            $response = new Response(200);
            try {
                $response = $page($request->withMethod($route->method), $response);
            } catch (\Exception $e) {
                \App::Redirect("404_not_found?msg=" . $e->getMessage());
            }

            foreach ($response->getHeaders() as $name => $values) {
                header($name . ": " . implode(", ", $values));
            }

            file_put_contents("php://output", (string)$response->getBody());
        } elseif (self::Logined()) {
            \App::Redirect("404_not_found");
        } else {
            \App::Redirect("");
        }
    }

    public static function Logined()
    {
        return (boolean)$_SESSION["app"]["login"];
    }

    public static function Init($root, $loader, $logger)
    {
        self::$app = new System($root, $loader, $logger);

        $p = explode(DIRECTORY_SEPARATOR, __DIR__);
        array_pop($p);
        array_pop($p);
        $path = implode(DIRECTORY_SEPARATOR, $p);

        define(SYSTEM, $path);
        define(CMS_ROOT, $root);

        $loader->addPsr4("", $root . "/class");
        $loader->addPsr4("", SYSTEM . "/class");


        spl_autoload_register(function ($class) use ($root) {

            $class_path = str_replace("\\", DIRECTORY_SEPARATOR, $class);
            $file = realpath($root . "/pages/$class_path/$class.class.php");
            if (is_readable($file)) {
                require_once($file);
            }
        });

        self::$app->loader = $loader;

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
