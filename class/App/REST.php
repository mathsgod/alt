<?
namespace App;

use R\Psr7\JSONStream;
use R\Psr7\Stream;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Exception;

class REST
{
    public static function Delete($uri)
    {
        $p = explode("/", $uri);
        $p = array_values(array_filter($p, "strlen"));
        $module = Module::_($p[0]);

        $class = "\\" . $module->class;
        $obj = new $class($p[1]);
        $obj->delete();
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response)
    {
        if (strtolower($request->getMethod()) == "delete") {
            try {
                self::Delete($request->getUri()->getPath());
            } catch (Exception $e) {

                foreach ($request->getHeader("accept") as $accept) {
                    if ($accept == "application/json") {
                        return $response->withBody(new JSONStream(["code" => $e->getCode(), "error" => $e]));
                    } else {
                        return $response->withBody(new Stream($e->getMessage()));
                    }
                }

            }


            foreach ($request->getHeader("accept") as $accept) {
                if ($accept == "application/json") {
                    return $response->withBody(new JSONStream(["code" => 200]));
                } else {
                    return $response->withBody(new Stream($module->name . " deleted"));
                }
            }
        }
        return $response;
    }

}