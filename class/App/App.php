<?

namespace App;

use R\Psr7\Response;
use R\Psr7\Stream;

use Cache\Adapter\Apcu\ApcuCachePool;
use Psr\Log\LoggerInterface;

class App extends \R\App
{
    private static $app;
    public $user;
    public $user_id;

    public static function _()
    {
        return self::$app;
    }

    public function __construct($root, $loader, $logger)
    {

        spl_autoload_register(function ($class) use ($root) {

            $class_path = str_replace("\\", DIRECTORY_SEPARATOR, $class);
            $file = realpath($root . "/pages/$class_path/$class.class.php");
            if (is_readable($file)) {
                require_once($file);
            }
        });


        parent::__construct($root, $loader, $logger);

        $p = explode(DIRECTORY_SEPARATOR, __DIR__);
        array_pop($p);
        array_pop($p);
        $path = implode(DIRECTORY_SEPARATOR, $p);

        $root = $this->root;

        define(SYSTEM, $path);
        define(CMS_ROOT, $root);

        $this->loader->addPsr4("", $root . "/class");
        $this->loader->addPsr4("", SYSTEM . "/class");

        self::$app = $this;

        //system config
        $pi = $this->pathInfo();
        $file = $pi["system_root"] . "/config.ini";
        if (file_exists($file)) {
            $c = parse_ini_file($file, true);


            foreach ($c as $n => $v) {
                foreach ($v as $a => $b) {
                    if (!isset($this->config[$n][$a])) {
                        $this->config[$n][$a] = $b;
                    }
                }

            }
        }

        //db config
        $host = $_SERVER["HTTP_HOST"];
        $pool = new ApcuCachePool();
        if ($pool->hasItem('config')) {
            $config = $pool->getItem($host . "_config")->get();
        } else {
            $item = $pool->getItem($host . "_config");

            $config = [];
            foreach (Config::Find() as $c) {
                $config[$c->name] = $c->value;
            }
            $item->set($config);
            $item->expiresAfter(60);
            $pool->save($item);
        }

        foreach ($config as $name => $value) {
            $this->config["user"][$name] = $value;
        }

        $this->alert = new Alert();

       //user
        if (!$_SESSION["app"]["user"]) {
            $_SESSION["app"]["user"] = new User(2);
        }
        $this->user = $_SESSION["app"]["user"];
        $this->user_id = $this->user->user_id;

    }

    public function getFile($file)
    {
        extract($this->pathInfo());

        if (is_readable($f = $cms_root . "/" . $file)) {
            return $f;
        }

        if (is_readable($f = $system_root . "/" . $file)) {
            return $f;
        }

    }

    public function pathInfo()
    {
        $s = $this->loader->getPrefixesPsr4()["ALT\\"][0];
        $s = explode("/../", $s, 2)[0];
        $composer_root = dirname(dirname($s));

        $server = $this->request->getServerParams();
        $document_root = $server["DOCUMENT_ROOT"];
        if (substr($document_root, -1) == "/") {
            $document_root = substr($document_root, 0, -1);
        }

        $composer_base = substr($composer_root, strlen($document_root));
        $composer_base = str_replace(DIRECTORY_SEPARATOR, "/", $composer_base);

        $cms_root = CMS_ROOT;
        $system_root = SYSTEM;
        $system_base = substr($system_root, strlen($document_root));
        $system_base = str_replace(DIRECTORY_SEPARATOR, "/", $system_base);

        return compact("composer_base", "composer_root", "document_root", "cms_root", "system_root", "system_base");
    }

    public function basePath()
    {
        return "//" . $_SERVER["SERVER_NAME"] . $this->request->getUri()->getBasePath();
    }

    public function run()
    {
        if ($this->logger) $this->logger->debug("APP::run");

        $this->base = $this->request->getUri()->getBasePath();

        $pi = $this->pathInfo();

        //load plugins
        $this->plugins_setting = \Symfony\Component\Yaml\Yaml::parseFile($pi["system_root"] . "/plugins.yml");

        foreach ($this->plugins_setting as $name => $value) {
            if ($value["locale"][$this->user->language]) {

                $this->plugins_setting[$name] = array_merge_recursive($this->plugins_setting[$name], $value["locale"][$this->user->language]);
            }
        }


        $path = $this->request->getUri()->getPath();

        $p = array_values(array_filter(explode("/", $path), "strlen"));

        $method = strtolower($this->request->getMethod());

        if (in_array("application/json", $this->request->getHeader("accept")) && count($p) <= 2) {
            if ((count($p) == 2 && is_numeric($p[1])) || $p[1] == null) {
            //check permission
                if ($method == "get") {
                    if (!ACL::Allow($p[0], "R")) {
                        http_response_code(403);
                        return;
                    }
                }

                $rest = new REST();
                $response = $rest($this->request, new Response(200));
                if ($code = $response->getStatusCode()) {
                    http_response_code($code);
                }
                file_put_contents("php://output", (string)$response->getBody());
                return;

            }
        }

//outp($this->plugins_setting);
//die();
        $router = new Router();
        $router->add("GET", "404_not_found", [
            "class" => "_404_not_found",
            "file" => $pi["system_root"] . "/pages/404_not_found.php"
        ]);

        ob_start();
        $route = $router->getRoute($this->request, $this->loader);
        $request = $this->request->withAttribute("included_content", ob_get_contents());
        ob_end_clean();

        $request = $request
            ->withAttribute("action", $route->action)
            ->withAttribute("route", $route);

        if (!$class = $route->class) {
            if ($this->user->isAdmin()) {
                $page = new ClassNotExistPage();
            }
        } else {
            $class = $route->class;
            $page = new $class($this);
        }

        $this->loader->addPsr4("", $pi["cms_root"] . "/pages");
        if ($page) {
            $response = new Response(200);
            try {
                $response = $page($request->withMethod($route->method), $response);
            } catch (\Exception $e) {
                $this->alert->danger($e->getMessage());

                $header = $this->request->getHeader("Referer");
                if ($h = $header[0]) {
                    $response = $response->withHeader("Location", $h);
                }
                
                //\App::Redirect("404_not_found?msg=" . $e->getMessage());
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

    public function redirect($url)
    {
        if ($uri) {
            $location = $this->request->getUri()->getBasePath() . "/" . $uri;
            $this->response = $this->response->withHeader("Location", $location);
            return;
        }

        if ($_GET["_referer"]) {
            $this->response = $this->response->withHeader("Location", $_GET["_referer"]);
            return;
        }

        $header = $this->request->getHeader("Referer");
        if ($h = $header[0]) {
            $this->response = $this->response->withHeader("Location", $h);
        }
    }

    public function logined()
    {
        return (boolean)$_SESSION["app"]["login"];
    }


    public function login($username, $password, $code)
    {
        if ($username == "") {
            throw new \Exception("Username cannot be empty", 400);
        }
        if ($password == "") {
            throw new \Exception("Password cannot be empty", 400);
        }

        //check AuthLock
        if (AuthLock::IsLock()) {
            throw new \Exception("IP locked 180 seconds", 403);
        }

        $sth = $this->db->prepare("select user_id,password from User where username=:username and status=0");
        $sth->execute([":username" => $username]);
        $row = $sth->fetch();
        $sth->closeCursor();
        if (is_null($row)) {
            AuthLock::Add();
            throw new \Exception("Login error", 403);
        }
        $user_id = $row["user_id"];
        $p = $row["password"];

        if (Util::Encrypt($password, $p) != $p) {
            AuthLock::Add();
            throw new \Exception("Login error", 403);
        }
        $user = new User($user_id);

        if ($this->config["user"]["2-step verification"]) {
            $need_check = true;
            if ($setting = $user->setting()) {
                if (in_array($_SERVER["REMOTE_ADDR"], $setting["2-step_ip_white_list"])) {
                    $need_check = false;
                }
            }

            if ($need_check && !System::IP2StepExemptCheck($_SERVER['REMOTE_ADDR'])) {
                if (($code == "" || !$user->checkCode($code)) && $user->secret != "") {
                    throw new \Exception("2-step verification", 403);
                }
            }
        }

        if ($user->expiry_date && strtotime($user->expiry_date) < time()) {
            AuthLock::Add();
            throw new \Exception("Login error", 403);
        }

        $_SESSION["app"]["user_id"] = $user_id;

        $_SESSION["app"]["user"] = $user;

        $_SESSION["app"]["login"] = true;

        $user->createUserLog("SUCCESS");

        $user->online();

        AuthLock::Clear();

        $this->user = $user;
    }

    public function flushMessage()
    {
        $msg = $_SESSION["app"]["message"];
        $_SESSION["app"]["message"] = [];
        return $msg;
    }

    public function version()
    {
        if ($_SESSION["app"]["version"])
            return $_SESSION["app"]["version"];
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

    public function savePlace()
    {
        $uri = $this->request->getURI();
        $path = $uri->getPath();

        if ($path[0] == "/") {
            $path = substr($path, 1);
        }

        if ($query = $uri->getQuery()) {
            $_SESSION["app"]["redirect"] = $path . "?" . $query;
        } else {
            $_SESSION["app"]["redirect"] = $path;
        }
    }


    public function sv($name, $lang)
    {
        if (!$lang) $lang = $this->user->language;

        if ($this->_sv[$name][$lang]) return $this->_sv[$name][$lang];

        if ($sv = SystemValue::_($name, $lang)) {
            $this->_sv[$name][$lang] = $sv->values();
            return $this->_sv[$name][$lang];
        }

        return [];
    }

    public function user()
    {
        return $this->user;
    }

    public function getModule()
    {
        $modules = Module::All();
        usort($modules, function ($a, $b) {
            if ($a->sequence == $b->sequence) {
                return 0;
            }
            return ($a->sequence < $b->sequence) ? -1 : 1;
        });

        return $modules;
    }

    public function t($str)
    {
        return Translate::_($str, $this->user->language);
    }

    public function twig($file)
    {
        $pi = pathinfo($file);

        $file = $pi["dirname"] . "/" . $pi["filename"];
        if (is_readable($template_file = $file . ".twig")) {

            if (!$config = $this->config["twig"]) {
                $config = [];
            }
            $root = $this->root;

            array_walk($config, function (&$o) use ($root) {
                $o = str_replace("{root}", $root, $o);
            });

            $twig["loader"] = new \Twig_Loader_Filesystem($root);
            $twig["environment"] = new \Twig_Environment($twig["loader"], $config);
            $twig["environment"]->addExtension(new \Twig_Extensions_Extension_I18n());

            $uri = substr($template_file, strlen($root) + 1);
            return $twig["environment"]->loadTemplate($uri);
        }
    }

    public function acl($path)
    {
        $user = $this->user;
        $raw_path = $path;
        $p = parse_url($path);
        $path = $p["path"];

        if ($p["scheme"]) { //external
            return true;
        }

        if ($path[0] == "/") { //absolute path
            $result = $user->isAdmin();

            $ugs = $user->UserGroup();

            $w = [];
            $w[] = ["path=?", $path];

            $u = [];
            $u[] = "user_id=" . $user->user_id;

            foreach ($ugs as $ug) {
                $u[] = "usergroup_id=$ug->usergroup_id";
            }

            $w[] = implode(" or ", $u);

            foreach (ACL::Find($w) as $acl) {
                $v = $acl->value();
                if ($v == "deny") {
                    return false;
                }
                if ($v == "allow") {
                    $result = true;
                }
            }

            return $result;
        }

        return ACL::Allow($path);



    }


}