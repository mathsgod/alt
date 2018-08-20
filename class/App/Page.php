<?php

namespace App;

use R\Psr7\Stream;
use R\Psr7\JSONStream;
use R\Set;

class Page extends \R\Page
{
    private $_template;
    private $_object;
    protected $route;
    public $_lib = [];
    private $data = [];

    protected $alert;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->alert = $app->alert;
    }

    public function _log($message, $array)
    {
        $log = \App::Logger();


        if ($message) {
            $log->info($message, $array);
        }

        return $log;
    }

    public function addLib($name)
    {
        if ($this->_lib[$name]) {
            return $this->_lib[$name];
        }
        $p = new Plugin($name);

        foreach ($p->setting["require"] as $require) {
            $this->addLib($require);
        }

        foreach ($p->setting["php"] as $php) {
            include_once($p->base . "/" . $php);
        }

        $this->_lib[$name] = $p;
        if ($name == "ckeditor") {
            $path = \App::Config("user", "roxy_fileman_path");
            $path = str_replace("{username}", $this->app->user->username, $path);
            $_SESSION["roxy_fileman_path"] = $path;
            mkdir(System::$root . "$path");
        }

        return $p;
    }

    public function template()
    {
        return $this->_template;
    }

    public function path()
    {
        $route = $this->request->getAttribute("route");
        return substr($route->path, 1);
    }

    public function id()
    {
        $path = $this->request->getURI()->getPath();
        foreach (explode("/", $path) as $q) {
            if (is_numeric($q)) {
                return $q;
            }
        }
    }


    public function ids()
    {
        $ids = [];
        $path = $this->request->getURI()->getPath();
        foreach (explode("/", $path) as $q) {
            if (is_numeric($q)) {
                $ids[] = $q;
            }
        }
        return $ids;
    }

    public function object()
    {
        if ($this->_object) {
            return $this->_object;
        }

        //check setting ini
        $route = $this->request->getAttribute("route");
        if (file_exists($file = dirname($route->file) . "/setting.ini")) {
            $ini = parse_ini_file($file, true);
            if ($ini["class"] == "App\System") {
                return;
            }

            if ($class = $ini["class"]) {
                $this->_object = new $class($this->id());
                return $this->_object;
            }
        }

        $class = "\\" . $this->module()->class;

        $id = $this->id();
        if (class_exists($class, true)) {
            $this->_object = new $class($id);
        }
        return $this->_object;
    }

    public function assign($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function _file()
    {
        $route = $this->request->getAttribute("route");
        return realpath($route->file);
    }

    public function _setting()
    {
        $p = explode("/", dirname($this->_file()));
        while (count($p)) {
            if (file_exists($file = implode("/", $p) . "/setting.ini")) {
                break;
            }
            array_pop($p);
        }

        if ($file) {
            return parse_ini_file($file, true);
        }
    }

    public function redirect($uri)
    {
        return $this->_redirect($uri);
    }

    public function _redirect($uri)
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

    public function __invoke($request, $response)
    {
        $this->request = $request;
        $this->request = $this->request->withAttribute("module", $this->module());

        $this->_object = $this->object();

        if ($this->app->logined()) {
            $this->app->user->online();
        }

        $route = $request->getAttribute("route");
        $path = substr($route->path, 1);
        $method = $route->method;

        if ($method == "del") {
            if ($request->getMethod() == "del" || $request->getMethod() == "post") {
                return parent::__invoke($request, $response);
            }
        }

        if (!ACL::Allow($path) && !ACL::Allow($path . "/" . $method)) {
            if ($request->isAccept("text/html")) {
                \App::AccessDeny($path);
                return;
            } elseif ($request->isAccept("application/json")) {
                return $response
                    ->withHeader("Content-Type", "application/json; charset=UTF-8")
                    ->withBody(new JSONStream(["code" => 401, "message" => "access deny"]));
            } else {
                \App::AccessDeny($path);
                return;
            }
        }



        if ($request->getQueryParams()["_rt"]) {
            $rt = new UI\RTResponse();
            $request = $request->withQueryParams(["rt" => $rt]);
        }

        ob_start();
        $response = parent::__invoke($request, $response);
        $echo_content = ob_get_contents();
        ob_end_clean();


        $action = $request->getAttribute("action");
        foreach ($this->request->HttpAccept() as $accept) {
            switch ($accept["media"]) {
                case "application/json":
                    if ($request->getMethod() == "get" && $action == "index") {
                        return $response
                            ->withHeader("Content-Type", "application/json; charset=UTF-8")
                            ->withBody(new JSONStream($this->object()));
                    }
                    if ($echo_content) {
                        $content = $echo_content;
                        $content .= (string)$response;

                        return $response->withBody(new Stream($content));
                    }
                    return $response;
                case "text/html":
                default:
                    if ($request->isAccept("text/html") || $request->isAccept("*/*")) {
                        if ($request->getMethod() == "get") {
                            $route = $this->request->getAttribute("route");
                            $file = $route->file;

                            
                            // CHECK HTML
                            if (is_readable($f = substr($file, 0. - 3) . "html")) {
                                $this->_template = file_get_contents($f);
                                $this->template_type = "html";
                            } elseif (is_readable($f = substr($file, 0, -3) . "twig")) {
                                $pi = pathinfo($f);
                                $template_path = $pi["dirname"];
                                $template_file = $pi["filename"] . ".twig";

                                $this->_twig["loader"] = new \Twig_Loader_Filesystem($template_path);
                                $this->_twig["environment"] = new \Twig_Environment($this->_twig["loader"]);
                                $this->_template = $this->_twig["environment"]->loadTemplate($template_file);
                            }
                        }
                    }
                    if ($this->template_type == "html") {
                        $content = (string)$response;
                        $content .= $this->_template;
                    } elseif ($this->_template instanceof \Twig_Template) {
                        $data = $this->data;
                        $ret = $response->getBody()->getContents();
                        if (is_array($ret)) {
                            $data = array_merge($data, $ret);
                        } else {
                            $content = $ret;
                        }

                        $content .= $echo_content;
                        $content .= $this->_template->render($data);
                        $response->setHeader("Content-Type", "text/html; charset=UTF-8");
                    } else {
                        $content = $echo_content;
                        $content .= (string)$response;
                    }

                    if ($request->getMethod() == "get") {
                        $content .= $this->request->getAttribute("included_content");
                    }

                    if ($request->getHeader("X-Requested-With")) {
                        if ($request->getQueryParams()["fancybox"]) {
                            $content = "<div style='width:80%'>" . $content . "</div>";
                        }
                    }

                    return $response->withBody(new Stream($content));
                    break;
            }
        }
    }

    public function module()
    {
        $route = $this->request->getAttribute("route");
        $path = $route->path;

        if ($module = Module::ByPath($path)) {
            return $module;
        }
        $p = explode("/", $path);
        $p = array_values(array_filter($p, strlen));
        return Module::_($p[0]);
    }

    public function translate($string)
    {
        return $this->module()->translate($string);
    }

    public function uri()
    {
        $uri = $this->request->getURI();
        $s = $uri->getPath();
        if ($query = $uri->getQuery()) {
            $s .= "?$query";
        }
        return substr($s, 1);
    }

    public function createForm($content = null, $multipart = false)
    {
        $request = $this->request;

        $f = new UI\Form($this);
        if ($multipart) {
            $f->attributes["enctype"] = "multipart/form-data";
        }

        $path = $this->path();
        $route = $request->getAttribute("route");

        if ($referer = $this->request->getHeader("Referer")[0]) {
            $params = $this->request->getQueryParams();
            $params["_referer"] = $referer;
            $request = $this->request->withQueryParams($params);

            $uri = $request->getUri();

            $f->attributes["action"] = $uri->getBasePath() . $uri->getPath() . "?" . $uri->getQuery();
        } else {
            $f->attributes["action"] = "#";
        }

        if ($content) {
            $f->addBody($content);
        }
        return $f;
    }

    public function createDataTables($objects)
    {
        return new UI\DataTables($this, $objects);
    }

    public function createT($objects)
    {
//        return new \ALT\T($objects,$this);
        return new UI\T($objects, $this);
    }

    public function createTab($prefix)
    {
        return new UI\Tab($this, $prefix);
    }


    public function createTable($objects)
    {
        return new UI\Table($objects, $this);
    }

    public function createButton()
    {
        return new UI\Button($this);
    }

    public function createBox($body)
    {
        $box = new UI\Box($this);
        $box->classList->add("box-primary");
        if ($body) {
            $box->body()->append($body);
        }
        return $box;
    }

    public function createDT($objects)
    {
        if ($objects instanceof \Iterator || $objects instanceof \IteratorAggregate) {
            $dt = new \App\UI\DataTables($objects, $this);
        } else {
            $dt = new \App\UI\DataTables(null, $this);
            $dt->serverSide = true;
            $dt->ajax["url"] = (string)$objects[0]->request->getURI() . "/" . $objects[1];
            $dt->boxStyle();
            $dt->pageLength = 25;
        }
        return $dt;
    }

    public function createDTResponse($query)
    {
        return new \App\UI\DTResponse($query);
    }


    public function createRT($objects, $module)
    {
        $rt = new UI\RT($objects, $module ? $module : $this->module(), $this->request);
        return $rt;
    }

    public function createRT2($objects, $module)
    {
        //$rt = new UI\RT($objects, $module ? $module : $this->module(), $this->request);
        $rt = new UI\RT2(null, $this, $this->app->config);
        $rt->ajax["url"] = (string)$objects[0]->request->getURI()->getPath() . "/" . $objects[1] . "?" . $this->request->getUri()->getQuery();
        $rt->ajax["url"] = substr($rt->ajax["url"], 1);


        $rt->pageLength = 25;
        return $rt;
    }

    public function session($name, $value)
    {
        $path = $this->request->getUri()->getPath();
        if (isset($value)) {
            $_SESSION["app"]["session"][$path][$name] = $value;
        } else {
            return $_SESSION["app"]["session"][$path][$name];
        }
    }

    public function del()
    {
        $obj = $this->object();
        $obj->delete();
        if ($this->request->isAccept("application/json") || $this->request->getHeader("X-Requested-With")) {
            return ["code" => 200];
        } else {
            $this->alert->success($this->module()->name . " deleted");
            $this->redirect();
        }
    }

    public function get()
    {
    }

    public function post()
    {

        $obj = $this->object();
        $id = $obj->id();
        $data = $this->request->getParsedBody();

        $params = $this->request->getQueryParams();


        if (isset($data["_pk"])) {
            $class = "\\" . $this->module()->class;
            $obj = new $class($data["_pk"]);
            $name = $data["name"];
            $value = $data["value"];
            $obj->$name = $value;
        } elseif (isset($params["xeditable"])) {
            $name = $data["name"];
            $value = $data["value"];
            $obj->$name = $value;
        } else {
            $obj->bind($data);

            if ($files = $this->request->getUploadedFiles()) {
                foreach ($files as $name => $file) {
                    $obj->$name = (string)$file->getStream();
                }

            }
        }

        $obj->save();
        if ($this->request->isAccept("application/json") || $this->request->getHeader("X-Requested-With")) {
            return ["code" => 200];
        } else {
            $msg = $this->module()->name . " ";
            if (method_exists($obj, '__toString')) {
                $msg .= (string)$obj . " ";
            }
            $msg .= $id ? "updated" : "created";
            $this->app->alert->success($msg);
            $this->redirect();
        }
    }

    public function __call($name, $args)
    {

        $path = $this->request->getUri()->getPath();
        $query = $this->request->getUri()->getQuery();
        $fragment = $this->request->getUri()->getFragment();

        $uri = $path
            . ($query ? '?' . $query : '')
            . ($fragment ? '#' . $fragment : '');

        throw new \Exception("method [$name] not found");

        \App::Redirect("404_not_found?uri=" . urlencode($uri));
    }

    public function getFormBuilder($path = null, $object = null)
    {
        $fb = FormBuilder::_($path ? $path : $this->path());
        $fb->setPage($this);
        return $fb;
    }
}
