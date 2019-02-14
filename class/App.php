<?php
use R\Psr7\ServerRequest;
use App\Composer;

class App
{
    public static $app;


    public static $system_mode = false;

    private static $_sv = [];
    private static $composer;
    private static $log;

    public static function Loader()
    {
        return self::_()->loader;
    }

    public static function Log()
    {
        $app = App\System::$app;
        return call_user_func_array([$app, "log"], func_get_args());
    }

    public static function User()
    {
        return self::_()->user;
    }

    public static function Config($category = null, $name = null)
    {
        if (func_num_args() == 0) {
            return App\System::$app->config;
        } elseif (func_num_args() == 1) {
            return App\System::$app->config[$category];
        }
        return App\System::$app->config[$category][$name];
    }

    public static function T($str = "")
    {
        return App\Translate::_($str, My::Language());
    }

    public static function Language()
    {
        return App\System::Language();
    }

    public static function Root()
    {
        return CMS_ROOT;
    }

    public static function Get($uri)
    {
        $system_root = realpath(__DIR__ . "/../pages");
        $user_root = realpath(__DIR__ . "/../../../pages");

        $route = new App\Route($uri, $system_root, $user_root, "GET");
        if (is_readable($route->real_path)) {
            include_once($route->real_path);
            $class = $route->class;
            return new $class($route);
        }
    }

    public static function Request($uri)
    {
        $system_root = realpath(__DIR__ . "/../pages");
        $user_root = realpath(__DIR__ . "/../../../pages");

        $request = ServerRequest::FromEnv();
        $uri = $request->getURI()->withPath($uri);
        $request = $request->withUri($uri);

        $route = new App\Route($request, self::Loader());

        if ($class = $route->class) {
            return new $class(self::_());
        }
    }

    public static function Redirect($uri)
    {
        // get base
        $app = self::_();

        $base = $app->base;

        if ($_GET["redirect"]) {
            header("location: " . $base . $_GET["redirect"]);
        } elseif ($uri == null) {
            header("location: " . $base . "/" . $_SESSION["app"]["redirect"]);
        } else {
            $data = parse_url($uri);
            if (!$data["scheme"]) {
                $_url = $base . "/" . $uri;
                header("location: $_url");
            } else {
                header("location: $url");
            }
        }
    }

    public static function UserID()
    {
        return self::_()->user->user_id;
    }

    public static function ACL($uri)
    {
        return App\ACL::Allow($uri);
    }

    public static function Msg($message, $type = "success")
    {
        self::_()->alert->success($message);
    }

    public static function Load($name)
    {
        $p = new App\Plugin($name);
        foreach ($p->setting["php"] as $php) {
            require_once($p->path . "/" . $php);
        }
    }

    public static function Skin()
    {
        $version = App::Version();

        $skins = [];
        foreach (glob("themes/*", GLOB_ONLYDIR) as $theme) {
            $skins[] = new ALT\Skin($theme);
        }

        return $skins;
    }

    public static function _()
    {
        return App\App::_();
    }
}
