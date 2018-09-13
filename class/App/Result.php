<?php
namespace App;

class Result implements \JsonSerializable
{

    public $data;
    public $error;

    public function jsonSerialize()
    {
        $ret = [];
        if ($this->error) {
            $ret["error"] = [];


            foreach ($this->error as $k => $e) {
                if (is_string($e)) {
                    if ($k == 0) {
                        $ret["error"]["message"] = $e;
                    }
                    $ret["error"]["errors"][] = ["message" => $e];
                } else {
                    if ($k == 0) {
                        $a = (array)$e;
                        foreach ($a as $k => $v) {
                            $ret["error"][$k] = $v;
                        }

                    }
                    $ret["error"]["errors"][] = $e;
                }
            }


            return $ret;
        }

        $ret = (array)$this;
        unset($ret["error"]);
        return $ret;
    }
}