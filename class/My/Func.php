<?php
namespace My;
use Exception;
class Func {
    private $function;
    private $parameter;
    public $id;

    public function name() {
        return $this->function;
    }

    public static function _($function, $parameters) {
        return new Func($function, $parameters);
    }

    public function __construct($function, $parameters) {
        $this->parameter = $parameters;
        $this->function = $function;
    }

    public function call($obj, $n) {
        if (is_null($this->function))return $obj;
        if ($this->function == "")return "";
        $func = $this->function;

        if ($func instanceof \Closure || is_array($func)) {
            $parameter = array();
            $parameter[] = $obj;
            $parameter[] = $n;
            foreach($this->parameter as $p) {
                $parameter[] = $p;
            }
            try {
                return call_user_func_array($func, $parameter);
            }
            catch(Exception $e) {
                return $e->getMessage();
            }
        }

        if (is_object($obj)) {
            if (in_array($func, array_keys(get_object_vars($obj)))) {
                $v = $obj->$func;
            } elseif (function_exists($func)) {
                $v = $func($obj);
            } else {
                try {
                    eval('$v=$obj->' . $func . ";");
                }
                catch(Exception $e) {
                     $v = $e->getMessage();
                }
            }
        } else {
            if (is_array($func)) {
                $v = $func[0]->$func[1]($obj);
            } elseif (function_exists($func)) {
                eval('$v=' . $func . '($obj);');
            } elseif (is_array($obj)) {
                $v = $obj[$func];
            } else {
                $v = $func;
            }
        } ;

        return $v;
    }
}

?>