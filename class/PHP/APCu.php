<?php
namespace PHP;

class APCu
{
    public $key;

    public static function Exists()
    {
        return function_exists("apcu_fetch");
    }

    public function __construct($key = "")
    {
        if ($key == "") {
            $key = self::GenerateKey();
        }
        $this->key = (string)$key;
    }

    public function delete()
    {
        return apcu_delete($this->key);
    }

    public function write($data)
    {
        if (apcu_exists($this->key)) {
            $this->delete();
        }
        apcu_store($this->key, $data);
    }

    public function read()
    {
        if (apcu_exists($this->key)) {
            return apcu_fetch($this->key);
        }
    }

    public function size()
    {
        return strlen($this->read());
//        $size = shmop_size($this->shm_id);
        return $size;
    }

    public static function GenerateKey($char = "t")
    {
        return ftok(__DIR__, $char);
    }
}
