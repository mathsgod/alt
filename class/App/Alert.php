<?php
namespace App;

class Alert
{
    public function info($message)
    {
        $_SESSION["app"]["message"][] = [
            "type" => "info",
            "message" => $message,
            "icon" => "fa fa-info"
        ];
    }

    public function success($message)
    {
        $_SESSION["app"]["message"][] = [
            "type" => "success",
            "message" => $message,
            "icon" => "fa fa-check"
        ];
    }

    public function danger($message)
    {
        $_SESSION["app"]["message"][] = [
            "type" => "danger",
            "message" => $message,
            "icon"=>"fa fa-exclamation-circle"
        ];
    }

    public function warning($message)
    {
        $_SESSION["app"]["message"][] = [
            "type" => "warning",
            "message" => $message,
            "icon"=>"fa fa-exclamation-triangle"
        ];
    }
}