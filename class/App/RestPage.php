<?php
namespace App;
class RestPage {
    public function __construct($route) {
        $this->route = $route;
    }

    public function __tostring() {
        try {
            $method = $this->route->method;
            $r_class = new \ReflectionClass(get_called_class());

            $data = [];
            foreach($r_class->getMethod($method)->getParameters() as $k => $param) {
                if ($_GET[$k]) {
                    $data[] = $_GET[$k];
                } else {
                    $data[] = $_GET[$param->name];
                }
            }

	       	header("Contact-Type: application/json");
           	$result["data"] = call_user_func_array(array($this, $method), $data);
            $result["code"] = 200;
            return json_encode($result, JSON_PRETTY_PRINT);
        }
        catch(\Exception $e) {
            $status = $e->getCode();
            if (!$status)$status = 500;
            header("HTTP/1.1 $status");
        	header("Contact-Type: application/json");
        	return json_encode(["code" => $status, "exception" => $e->getMessage()]);
        }
    }
}

?>