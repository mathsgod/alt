<?
namespace Type;

use Exception;
use GraphQL\Error\Error;

class Query
{

    public function me($root, $args, $context)
    {
        if ($context->user->user_id == 2) return null;
        return $context->user;
    }

    public function login($root, $args, $context)
    {
        try {
            $context->login($args["username"], $args["password"], $args["code"]);
            return true;
        } catch (Exception $e) {
            throw new Error($e->getMessage());
        }
    }

    public function forgotPassword($root, $args, $context)
    {
        $w[] = ["username=?", $args["username"]];
        $w[] = ["email=?", $args["email"]];
        if ($user = \App\User::first($w)) {
            try {
                $user->sendPassword();
            } catch (Exception $e) {
                throw new Error($e->getMessage());
            }
        }
        return true;
    }
}