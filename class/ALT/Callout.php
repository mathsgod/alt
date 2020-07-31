<?php
namespace ALT;

class Callout extends \ArrayObject
{
    public function info($title, $description)
    {
        $this->append([
            "type" => "info",
            "title" => $title,
            "description" => $description
        ]);
    }

    public function success($title, $description)
    {
        $this->append([
            "type" => "success",
            "title" => $title,
            "description" => $description
        ]);
    }

    public function danger($title, $description)
    {
        $this->append([
            "type" => "danger",
            "title" => $title,
            "description" => $description
        ]);
    }

    public function warning($title, $description)
    {
        $this->append([
            "type" => "warning",
            "title" => $title,
            "description" => $description
        ]);
    }
}