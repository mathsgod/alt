<?php
namespace My;
interface Object {
    public function id();

    public function canCreate();
    public function canRead();
    public function canUpdate();
    public function canDelete();

    public function uri($v);

    public function delete();
}

?>