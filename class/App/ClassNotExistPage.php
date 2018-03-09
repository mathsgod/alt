<?php

namespace App;

use R\Psr7\Stream;

class ClassNotExistPage extends \ALT\Page
{

    public function __invoke($request, $response)
    {

        $route=$request->getAttribute("route");
        $path=$route->path;

        $query=$request->getQueryParams();
        $query=array_map(function ($q) {
            return "$".$q;
        }, array_keys($query));

        $query_str=implode(",", $query);

        $class=str_replace("/", "_", $path);

        $content=<<<EOT

class not exists<br/>
copy follow code to <b>{$path}.php</b>
<br/>

<code style=display:block;white-space:pre-wrap>
&lt;?php
class {$class} extends ALT\Page{

    public function get($query_str){

    }
}

</code>

EOT
        ;

        $namespace=dirname($path);
        if ($namespace!=".") {
            $namespace=str_replace("-", "_", $namespace);
            $basename=basename($path);
            $basename=str_replace("-", "_", $basename);
        
            $content.="<br/>";
            $content.=<<<EOT
<code style=display:block;white-space:pre-wrap>
&lt;?php
namespace $namespace;
class {$basename} extends \ALT\Page{

    public function get($query_str){

    }
}

</code>
        
EOT;
        }
        
        $response=$response->withBody(new Stream($content));
        return parent::__invoke($request, $response);
    }
}
