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
        return self::$app->loader;
    }

    public static function Log()
    {
        $app = App\System::$app;
        return call_user_func_array([$app, "log"], func_get_args());
    }

    public static function Logger()
    {
        if (App\System::$app->logger) {
            return App\System::$app->logger;
        }
        if (self::$log) {
            return self::$log;
        }
        $log = new Monolog\Logger("app");

        if (self::Composer()->hasPackage("php-console/php-console")) {
            $log->pushHandler(new Monolog\Handler\PHPConsoleHandler());
        }
        return self::$log = $log;
    }

    public static function Composer()
    {
        if (self::$composer) {
            return self::$composer;
        }
        return self::$composer = new Composer();
    }

    public static function Version()
    {
        return self::_()->version();
    }

    public static function User()
    {
        if (!$_SESSION["app"]["user"]) {
            $_SESSION["app"]["user"] = new App\User(2);
        }
        return $_SESSION["app"]["user"];
    }

    public static function SV($name, $lang)
    {
        if (!$lang) {
            $lang = My::Language();
        }
        if (self::$_sv[$name]) {
            return self::$_sv[$name];
        }
        if ($sv = App\SystemValue::_($name, $lang)) {
            self::$_sv[$name] = $sv->values();
            return $sv->values();
        }
        return [];
    }

    public static function IsSystemMode()
    {
        return self::$system_mode;
    }

    public static function SystemMode()
    {
        self::$system_mode = true;
    }

    public static function SavePlace()
    {
        App\System::SavePlace();
    }

    public static function Config($category = null, $name = null)
    {
        if (func_num_args() == 0) {
            return App\Config::All();
        } elseif (func_num_args() == 1) {
            return App\Config::All()[$category];
        }
        return App\Config::All()[$category][$name];
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
            return new $class();
        }
    }

    public static function Redirect($uri)
    {
        // get base
        $base = App\System::$base;
        if ($_GET["redirect"]) {
            header("location: " . $base . $_GET["redirect"]);
        } elseif ($uri == null) {
            header("location: " . $base . $_SESSION["app"]["redirect"]);
        } else {
            $data = parse_url($uri);
            if (!$data["scheme"]) {
                $_url = $base . $uri;
                header("location: $_url");
            } else {
                header("location: $url");
            }
        }
    }

    public static function Path($uri)
    {

        extract(self::_()->pathInfo());

        if (is_readable($f = $cms_root . "/" . $uri)) {
            return $f;
        }

        if (is_readable($f = $system_root . "/" . $uri)) {
            return $f;
        }


    }

    public static function SystemPath($uri)
    {
        $f = App\System::$app->root . "/composer/vendor/hostlink/r-alt/" . $uri;
        return $f;
    }

    public static function UserID()
    {
        return $_SESSION["app"]["user"]->user_id;
    }

    public static function ACL($uri)
    {
        return App\ACL::Allow($uri);
    }

    public static function Msg($message, $type = "success")
    {
        App\System::Msg($message, $type);
    }

    public static function DB()
    {
        return self::$app->db;
    }

    public static function Module()
    {
        $modules = App\Module::All();
        usort($modules, function ($a, $b) {
            if ($a->sequence == $b->sequence) {
                return 0;
            }
            return ($a->sequence < $b->sequence) ? -1 : 1;
        });


        return $modules;
    }

    public static function AccessDeny($uri)
    {
        if (App\System::Logined()) {
            App::Redirect("access_deny");
        } else {
            App::Redirect("?r=$uri");
        }
        exit();
    }

    public static function Load($name)
    {
        $p = new App\Plugin($name);
        foreach ($p->setting["php"] as $php) {
            require_once($p->path . "/" . $php);
        }
    }

    public static function TPL($file)
    {
        $file = App::Path($file);

        if (!$file) {
            throw new Exception("file($file) not found");
        }


        $content = file_get_contents($file);
        foreach (\App::Language() as $la => $lv) {
            if ($la == \My::Language()) {
                $content = preg_replace("/<:{$la}>([\s\S]*?)<\/:{$la}>/", "$1", $content);
                $content = preg_replace("/<(\w+):{$la}([^>]*)\/>/", "<$1 $2/>", $content);
                $content = preg_replace("/<(\w+):{$la}([^>]*)>([\s\S]*?)<\/(\w+):{$la}>/", "<$1 $2>$3</$4>", $content);
            } else {
                $content = preg_replace("/<:{$la}>[\s\S]*?<\/:{$la}>/", "", $content);
                $content = preg_replace("/<(\w+):{$la}([^>]*)\/>/", "", $content);
                $content = preg_replace("/<(\w+):{$la}([^>]*)>([\s\S]*?)<\/(\w+):{$la}>/", "", $content);
            }
        }


        $tpl = new \TemplatePower($content, T_BYVAR);
        $tpl->prepare();
        return $tpl;
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

    public static function SystemModeStart()
    {
        return self::_()->systemModeStart();
    }

    public static function SystemModeEnd()
    {
        return self::_()->systemModeEnd();
    }

    public static function _()
    {
        return App::$app;
    }

    public static function SystemBase()
    {
        $p = self::_()->pathInfo();
        return $p["system_base"];
    }
}
