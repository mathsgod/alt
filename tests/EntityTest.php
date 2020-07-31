<?php
declare (strict_types = 1);
error_reporting(E_ALL && ~E_WARNING);
use PHPUnit\Framework\TestCase;

final class EntityTest extends TestCase
{
    public function testCreate()
    {
        $loader = new Composer\Autoload\ClassLoader();
        $app = new App\App(__DIR__, $loader);
        $this->assertInstanceOf(App\Entity::class, $app->entity);
    }

    public function testUser()
    {
        $loader = new Composer\Autoload\ClassLoader();
        $app = new App\App(__DIR__, $loader);

        $u = $app->entity->User(1);
        $this->assertInstanceOf(App\User::class, $u);
        $this->assertEquals($u->username, "admin");


        $u = $app->entity->User;
        $this->assertInstanceOf(R\ORM\Query::class, $u);

    }
}