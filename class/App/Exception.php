<?

namespace App;

class Exception extends \Exception
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        // make sure everything is assigned properly

        if (is_string($message)) {
            $msg = [];
            $msg["error"] = [];
            $msg["error"]["code"] = $code;
            $msg["error"]["message"] = $message;

            $message = json_encode($msg, JSON_UNESCAPED_UNICODE);
        }

        parent::__construct($message, 0, $previous);
    }
}