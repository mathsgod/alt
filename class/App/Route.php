<?php

namespace App;

class Route extends \R\Route
{
    public function __construct($request, $loader)
    {
        if (!$request) {
            return;
        }
        $uri = $request->getUri();
        $this->uri = (string)$uri;
        $this->path = $uri->getPath();
        $this->basePath = $uri->getBasePath();

        if ($this->path == "/") {
            $this->path = "/index";
        }


        if (substr($this->path, -1) == "/") {
            $this->no_index = true;
            $this->path .= "index";
        }

        $this->method = strtolower($request->getMethod());
        parse_str($uri->getQuery(), $this->query);

        // skip id
        $t = [];
        foreach (explode("/", $this->path) as $q) {
            $q = trim($q);
            if (is_numeric($q)) {
                $this->ids[] = $q;
                if (!$this->id) {
                    $this->id = $q;
                }
                continue;
            }
            if ($q) {
                $t[] = $q;
            }
        }

        $this->path = "/" . implode("/", $t);

        $this->psr0($request, $loader);

        if (is_readable($this->file)) {
            require_once($this->file);
        }
        if (class_exists($this->class, false)) {
            $loader->addClassMap([$this->class => $this->file]);
            return;
        }

  
        if ($this->class[0] == "_") {
            $class = substr($this->class, 1);
            if (class_exists($class, false)) {
                $this->class = $class;
                $loader->addClassMap([$class => $this->file]);
                return;
            }
        }

        return;
        $this->psr4($request, $loader);
        $class = str_replace("-", "_", $this->class);
        $class = str_replace("\\", "_", $class);
        if (file_exists($this->file)) {
            require_once($this->file);
        }
        if (class_exists($class, false)) {
            $this->class = $class;
        }
    }

    public function psr0($request, $loader)
    {
        $qs = explode("/", $this->path);
        $method = strtolower($request->getMethod());
        $root = $request->getServerParams()["DOCUMENT_ROOT"];
        $base = $request->getURI()->getBasePath();

        $page = \App::Config("system", "pages");
        if (!$page) {
            $page = "/pages";
        }

        while (count($qs)) {
            $path = implode("/", $qs);


            if (file_exists($file = $root . $base . $page . $path . "/index.php")) {
                $this->file = $file;
                $this->path = implode("/", $qs) . "/index";
                $this->class = implode("_", $qs) . "_index";
                $this->action = "index";
                $this->method = $method;
                return;
            }

            if (file_exists($file = SYSTEM . DIRECTORY_SEPARATOR . "pages" . $path . DIRECTORY_SEPARATOR . "index.php")) {

                $this->file = $file;
                $this->path = implode("/", $qs) . "/index";
                $this->class = implode("_", $qs) . "_index";
                $this->action = "index";
                $this->method = $method;
                return;
            }

            if (file_exists($file = $root . $base . $page . $path . ".php")) {
                $this->file = $file;
                $this->path = $path;
                $this->class = implode("_", $qs);
                $this->action = array_pop($qs);
                $this->method = $method;
                return;
            }

            if (file_exists($file = SYSTEM . DIRECTORY_SEPARATOR . "pages" . $path . ".php")) {
                $this->file = $file;
                $this->path = implode("/", $qs);
                $this->class = implode("_", $qs);
                $this->action = array_pop($qs);
                $this->method = $method;
                return;
            }

            $method = array_pop($qs);
        }
    }
}
