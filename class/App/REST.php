<?
namespace App;

use R\Psr7\JSONStream;
use R\Psr7\Stream;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Exception;

class REST
{

    public static function IsValid(RequestInterface $request)
    {

        if (!in_array("application/json", $request->getHeader("accept"))) {
            return false;
        }

        if(strpos("application/json",$request->getHeader("content-type")[0])==-1){
            return false;
        }


        return true;
    }

    //create data
    public static function Post($uri, $data)
    {
        $p = parse_url($uri);
        $p = $p["path"];
        $p = substr($p, 1);
        $p = explode("/",$p);

        $module = Module::_($p[0]);
        $class = "\\" . $module->class;

        
        if($pk=$data["_pk"]){

            $o=new $class($pk);
            $name=$data["name"];
            $o->$name=$data["value"];
            $o->save();
            return ["code" => 200, "location" => $p . "/" . $o->id()];
        }


        $obj = new $class($p[1]);
        $obj->bind($data);
        $obj->save();
        return ["code" => 200, "location" => $p[0] . "/" . $obj->id()];
    }

    //
    public static function Get($uri)
    {
        $p = parse_url($uri);
        $query = [];
        parse_str($p["query"], $query);

        $params = [];
        foreach ($query as $k => $v) {
            if ($k[0] != '$') {
                $params[$k] = $v;
            }
        }

        $orderBy = $query['$orderBy'];

        $path = $p["path"];

        $p = explode("/", $path);
        $p = array_values(array_filter($p, "strlen"));
        $module = Module::_($p[0]);

        $class = "\\" . $module->class;

        if (count($p) == 1) {
            $value = $class::Query($params);

            if ($orderBy) {
                $value = $value->orderBy($orderBy);
            }
            $value = $value->toArray();
            return ["data" => $value];
        } elseif (count($p) == 2) {
            try {
                $value = new $class($p[1]);
                return $value;
            } catch (Exception $e) {
                return ["error" => ["code" => $e->getCode(), "message" => $e->getMessage()]];
            }
        }
    }

    //update partial data
    public static function Patch($uri, $data)
    {
        $p = explode("/", $uri);
        $p = array_values(array_filter($p, "strlen"));
        $module = Module::_($p[0]);

        $class = "\\" . $module->class;
        $obj = new $class($p[1]);
        $obj->bind($data);
        $obj->save();
        return ["code" => 200];
    }

    //replace whole data
    public static function Put($uri, $data)
    {
        $p = explode("/", $uri);
        $p = array_values(array_filter($p, "strlen"));
        $module = Module::_($p[0]);

        $class = "\\" . $module->class;
        $obj = new $class($p[1]);
        $obj->bind($data);
        $obj->save();
        return ["code" => 200];
    }

    //delete
    public static function Delete($uri)
    {
        $p = explode("/", $uri);
        $p = array_values(array_filter($p, "strlen"));
        $module = Module::_($p[0]);

        $class = "\\" . $module->class;
        $obj = new $class($p[1]);
        $obj->delete();

        return ["code" => 200];
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response)
    {

        $method = strtolower($request->getMethod());

        $uri = $request->getUri();
        $uri = $uri->getPath() . "?" . $uri->getQuery();

        try {
            if ($method == "patch") {
                $ret = self::Post($uri, $request->getParsedBody());
            } elseif ($method == "get") {
                $ret = self::Get($uri);
            } elseif ($method == "post") {
                $ret = self::Post($uri, $request->getParsedBody());
            } else if ($method == "delete") {
                $ret = self::Delete($uri);
            } elseif ($method == "put") {
                $ret = self::Put($uri, $request->getParsedBody());
            }

            $response = $response->withBody(new JSONStream($ret));

        } catch (Exception $e) {
            $response = $response->withBody(new JSONStream([
                "code" => 200,
                "error" => ["message" => $e->getMessage()]
            ]));
        }

        return $response;
    }

}