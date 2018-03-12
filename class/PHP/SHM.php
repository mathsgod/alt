<?php
namespace PHP;
class SHM {
    public $shm_key;
    public $shm_id;

    public static function Exists()
    {
        return function_exists("ftok");
    }


    public function __construct($shm_key = "") {
        if ($shm_key == "") {
            $shm_key = SHM::GenerateKey();
        }
        $this->shm_key = $shm_key;

        $this->shm_id = shmop_open($this->shm_key, "a", 0, 0);
    }

    public function delete() {
        if ($shmid = shmop_open($this->shm_key, "a", 0, 0)) {
            shmop_delete($shmid);
        }
    }

    public function write($data) {
        $str = json_encode($data);

        if ($shmid = shmop_open($this->shm_key, "a", 0, 0)) {
            shmop_delete($shmid);
        }
        // check shm
        $this->shm_id = shmop_open($this->shm_key, "c", 0644, strlen($str));

        $i = shmop_write($this->shm_id, $str, 0);

        return $i;
    }

    public function read() {
        $str = shmop_read($this->shm_id, 0, shmop_size($shmid));
        return json_decode($str,true);
    }

    public function size() {
        $size = shmop_size($this->shm_id);
        return $size;
    }

    public static function GenerateKey($char = "t") {
        return ftok(__DIR__, $char);
    }
}

?>