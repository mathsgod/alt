<?

namespace App;

use R\Psr7\Response;
use R\Psr7\Stream;

class App extends \R\App
{
    public function __construct($root, $loader)
    {
        parent::__construct($root, $loader);

        $p = explode(DIRECTORY_SEPARATOR, __DIR__);
        array_pop($p);
        array_pop($p);
        $path = implode(DIRECTORY_SEPARATOR, $p);


        $root = $this->root;

        define(SYSTEM, $path);
        define(CMS_ROOT, $root);

        $this->loader->addPsr4("", $root . "/class");
        $this->loader->addPsr4("", SYSTEM . "/class");


        spl_autoload_register(function ($class) use ($root) {

            $class_path = str_replace("\\", DIRECTORY_SEPARATOR, $class);
            $file = realpath($root . "/pages/$class_path/$class.class.php");
            if (is_readable($file)) {
                require_once($file);
            }
        });

        \App::$app = $this;
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

    public function basePath(){
        return "//" . $_SERVER["SERVER_NAME"] . $this->request->getUri()->getBasePath();
    }

    public function log($s)
    {
        if ($this->logger) {
            $this->logger->info($s);
        }
    }

    public function run()
    {
        $this->log("run");

        session_start();
        $this->base = $this->request->getUri()->getBasePath();

        $b = $this->pathInfo();


        $router = new Router();
        $router->add("GET", "404_not_found", [
            "class" => "_404_not_found",
            "file" => $b["system_root"] . "/pages/404_not_found.php"
        ]);

        ob_start();
        $route = $router->getRoute($this->request, $this->loader);
        $request = $this->request->withAttribute("included_content", ob_get_contents());
        ob_end_clean();

        $request = $request
            ->withAttribute("action", $route->action)
            ->withAttribute("route", $route);


        if (!$class = $route->class) {
            if (\App::User()->isAdmin()) {
                $page = new ClassNotExistPage();
            }
        } else {
            $class = $route->class;
            $page = new $class($this);
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

        if (\App::Config("user", "2-step verification")->value) {
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



}