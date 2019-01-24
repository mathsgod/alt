<?php
namespace Type;

class Mutation
{
    public function me($root, $args, $context)
    {
        if ($context->user->user_id == 2) return null;
        return $context->user;
    }
}
