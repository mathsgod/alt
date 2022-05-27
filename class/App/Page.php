<?php

namespace App;

use R\Psr7\Stream;
use R\Psr7\JSONStream;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Template;

class Page extends \R\Page
{
    private $_template;
    private $_object;
    protected $route;
    public $_lib = [];
    private $data = [];

    protected $alert;

    /**
     * @var ServerRequestInterface $request
     */
    protected $request;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->alert = $app->alert;
    }

    public function _log($message, $array)
    {
        if ($log = $this->logger) {
            if ($message) {
                $log->info($message, $array);
            }
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
            $path = $this->app->config["user"]["roxy_fileman_path"];
            $path = str_replace("{username}", $this->app->user->username, $path);
            $_SESSION["roxy_fileman_path"] = $path;

            $pi = $this->app->pathinfo();
            $path = $pi["system_root"] . $path;
            mkdir($path);
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
            try {
                $this->_object = new $class($id);
            } catch (\Exception $e) {
                return null;
            }
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

    public function redirect($uri = null)
    {
        return $this->_redirect($uri);
    }

    public function _redirect($uri = null)
    {
        if ($uri) {
            $prelink = $this->request->getUri()->getBasePath();
            $location = $prelink . "/" . $uri;
            $this->response = $this->response->withHeader("Location", $location);
            return;
        }

        if ($_GET["_referer"]) {
            $this->response = $this->response->withHeader("Location", $_GET["_referer"]);
            return;
        }

        if ($referer = $this->request->getHeader("Referer")[0]) {
            if ($url = $_SESSION["app"]["referer"][$referer]) {
                $this->response = $this->response->withHeader("Location", $url);
                return;
            }

            $this->response = $this->response->withHeader("Location", $referer);
        }
    }


    function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->request = $request;
        $this->request = $this->request->withAttribute("module", $this->module());

        if ($this->app->logined()) {
            $this->app->user->online();
        }

        $this->_object = $this->object();

        $route = $request->getAttribute("route");
        $path = substr($route->path, 1);
        $method = $route->method;
        if ($method == "del") {
            if ($request->getMethod() == "del" || $request->getMethod() == "post") {
                return parent::__invoke($request, $response);
            }
        }

        if (!$this->app->acl($path) && !$this->app->acl($path . "/" . $method)) {
            return $this->app->accessDeny($request);
        }

        if ($request->getMethod() == "get") {
            if (!$this->_object && $this->id()) {
                $this->response = $response;
                $this->redirect($this->module()->name);
                return $response;
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
        //get accept
        $accept = $request->getHeader("Accept");
        if ($accept) {
            $accept = $accept[0];
        }
        //explode accept
        $accepts = explode(",", $accept);
        $accepts = array_map(function ($a) {
            return trim($a);
        }, $accepts);

        foreach ($accepts as $accept) {
            switch ($accept) {
                case "application/json":
                    if ($request->getMethod() == "get" && $action == "index") {
                        return $response
                            ->withHeader("Content-Type", "application/json; charset=UTF-8")
                            ->withBody(new JSONStream($this->object()));
                    }
                    if ($echo_content) {
                        $content = $echo_content;
                        $content .= $response->getBody()->getContents();

                        return $response->withBody(new Stream($content));
                    }
                    return $response;
                case "text/html":
                default:
                    if (in_array("text/html", $accepts) || in_array("*/*", $accepts)) {
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

                                $this->_twig["loader"] = new FilesystemLoader($template_path);
                                $this->_twig["environment"] = new Environment($this->_twig["loader"]);
                                $this->_template = $this->_twig["environment"]->load($template_file);
                            }
                        }
                    }
                    if ($this->template_type == "html") {
                        $content = $response->getBody()->getContents();
                        $content .= $this->_template;
                    } elseif ($this->_template instanceof Template) {
                        $data = $this->data;
                        $ret = $response->getBody()->getContents();
                        if (is_array($ret)) {
                            $data = array_merge($data, $ret);
                        } else {
                            $content = $ret;
                        }
                        $data["_object"] = $this->object();

                        $data["_app"] = $this->app;

                        $content .= $echo_content;
                        $content .= $this->_template->render($data);
                        $response = $response->withHeader("Content-Type", "text/html; charset=UTF-8");
                    } else {
                        $content = $echo_content;
                        $content .= $response->getBody()->getContents();
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

        return $response->withBody(new Stream($echo_content . $response->getBody()->getContents()));
    }

    public function module()
    {
        $route = $this->request->getAttribute("route");
        $path = $route->path;

        return Module::ByPath($path);
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

        $f = new UI\Form($this);
        if ($multipart) {
            $f->setAttribute("enctype", "multipart/form-data");
        }


        if ($referer = $this->request->getHeader("Referer")[0]) {

            $uri = $this->request->getUri()->withUserInfo(null, null);
            $_SESSION['app']["referer"][(string)$uri] = $referer;
        }
        if ($content) {
            $f->addBody($content);
        }
        return $f;
    }

    public function createDataTable($objects)
    {
        return new UI\DataTables($objects, $this);
    }

    public function createT($objects): UI\T
    {
        return new UI\T($objects, $this);
    }

    public function createTab($prefix = null)
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


    public function createRT($objects, $module = null)
    {
        $rt = new UI\RT($objects, $module ? $module : $this->module(), $this->request);
        return $rt;
    }

    public function createRT2($objects, $module = null)
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

        //get accept
        $accept = $this->request->getHeaderLine("Accept");
        if (strpos($accept, "application/json") !== false || $this->request->hasHeader("X-Requested-With")) {
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
        $accept = $this->request->getHeaderLine("Accept");
        if (strpos($accept, "application/json") !== false || $this->request->hasHeader("X-Requested-With")) {
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

        $this->redirect("404_not_found?uri=" . urlencode($uri));
    }

    public function getFormBuilder($path = null, $object = null)
    {
        $fb = FormBuilder::_($path ? $path : $this->path());
        $fb->setPage($this);
        return $fb;
    }

    public function creatorBox($object)
    {
        if (!$object) {
            $object = $this->object();
        }
        if ($object) {
            $v = $this->createV($object);
            if (property_exists($object, "created_by")) {
                $v->add("Created by", "createdBy()");
            }
            if (property_exists($object, "created_time")) {
                $v->add("Created time", "created_time");
            }
            if (property_exists($object, "updated_by")) {
                $v->add("Updated by", "updatedBy()");
            }
            if (property_exists($object, "updated_time")) {
                $v->add("Updated time", "updated_time");
            }
            $v->header("Creator information");
            return $v;
        }
    }

    public function createV($object = null)
    {
        if (!$object) {
            $object = $this->object();
        }
        return new UI\V($object, $this);
    }
}
