<?
declare (strict_types = 1);
error_reporting(E_ALL && ~E_WARNING);
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function testLogin()
    {
        $loader = new Composer\Autoload\ClassLoader();
        $app = new App\App(__DIR__, $loader);
        $this->assertTrue((boolean)App\App::Login("raymond", "111111"));
        $this->assertFalse(App\User::Login("raymond", "222222"));
    }
}