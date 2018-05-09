<?
namespace App;

class Alert
{
    public function info($message)
    {
        $_SESSION["app"]["message"][] = [
            "type" => "info",
            "message" => $message
        ];
    }

    public function success($message)
    {
        $_SESSION["app"]["message"][] = [
            "type" => "success",
            "message" => $message
        ];

    }

    public function danger($message)
    {
        $_SESSION["app"]["message"][] = [
            "type" => "danger",
            "message" => $message
        ];
    }

}