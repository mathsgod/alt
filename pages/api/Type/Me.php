<?
namespace Type;

class Me
{
    public function group($user, $args, $context)
    {
        return $user->UserGroup();
    }
}