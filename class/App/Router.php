<?php
namespace App;

class Router extends \R\Router
{
    public function getRoute($request, $loader)
    {
        $document_root = $request->getServerParams()["DOCUMENT_ROOT"];
        $base = $request->getUri()->getBasePath();
        $path = $request->getUri()->getPath();

        $path = array_filter(explode("/", $path), function ($o) {
            return !is_numeric($o);
        });
        $path = implode("/", $path);


        $method = $request->getMethod();
        foreach ($this->route as $route) {
            if ($method == $route["method"] && $path == $route["path"]) {
                $r = new Route();

                $r->path = $path;
                $r->uri = (string)$request->getURI();
                $r->class = $route["params"]["class"];
                $r->method = strtolower($route["method"]);
                $r->action = basename($route["path"]);
                parse_str($request->getURI()->getQuery(), $r->query);
                $r->file = $route["params"]["file"];

                $loader->addClassMap([
                    $r->class => $r->file
                ]);

                return $r;
            }
        }
        
        return new Route($request, $loader);
    }
}
