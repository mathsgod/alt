<?php
namespace App;

class Route extends \R\Route
{
    public function __construct($request, $loader)
    {
        
        if (!$request) {
            return;
        }
        
        $uri=$request->getUri();
        $this->uri=(string)$uri;
        $this->path=$uri->getPath();

        if ($this->path=="") {
            $this->path="index";
        }
        if (substr($this->path, - 1) == "/") {
            $this->no_index = true;
            $this->path .= "index";
        }

        $this->method=strtolower($request->getMethod());
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
       
        $this->path=implode("/", $t);

        $this->psr0($request, $loader);
        if (file_exists($this->file)) {
            require_once($this->file);
        }

        if (class_exists($this->class, false)) {
            $loader->addClassMap([$this->class=>$this->file]);
            return;
        }
        
        $class=str_replace("-", "_", $this->class);

        if (class_exists($class, false)) {
            $this->class=$class;
            $loader->addClassMap([$class=>$this->file]);
            return;
        }

        $this->psr4($request,$loader);
        $class=str_replace("-", "_", $this->class);
        $class=str_replace("\\", "_", $class);
        if (file_exists($this->file)) {
            require_once($this->file);
        }
        if(class_exists($class,false)){
            $this->class=$class;
            
        }

        

    }

    public function psr0($request, $loader)
    {
        $qs = explode("/", $this->path);
        $method=strtolower($request->getMethod());
        $root=$request->getServerParams()["DOCUMENT_ROOT"];
        $base=$request->getURI()->getBasePath();

        while (count($qs)) {
            $path=implode("/", $qs);
                        
            if (file_exists($file = $root."/".$base."/pages/".$path.".php")) {
                $this->file=$file;
                $this->path=$path;
                $this->class= implode("_", $qs);
                $this->action=array_pop($qs);
                $this->method=$method;
                break;
            }
            $method = array_pop($qs);
        }
    }
}
