<?
declare (strict_types = 1);
error_reporting(E_ALL && ~E_WARNING);
use PHPUnit\Framework\TestCase;

final class AppTest extends TestCase
{

    public function testApp()
    {
        $app = new App\App(__DIR__);

        $this->assertEquals($app, App::_());

    }

}