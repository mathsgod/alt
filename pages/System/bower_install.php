<?php

class System_bower_install extends App\Page {
    public function get() {
        $d = getcwd();
        // require_once('/home/vhosts/raymond2/public_html/cms4/composer/composer/vendor/autoload.php');
        require_once('/home/vhosts/raymond2/public_html/cms4/system/source/composer/vendor/autoload.php');

        putenv('HOME=/home/vhosts/raymond2/public_html/cms4/system/source/bower');

        chdir("/home/vhosts/raymond2/public_html/cms4/system/source/bower");

        $input = new Symfony\Component\Console\Input\ArrayInput(array('command' => "install", "package" => "jquery"));

        $output = new Symfony\Component\Console\Output\BufferedOutput();
        // Create the application and run it with the commands
        try {
            $application = new Bowerphp\Console\Application();

            $application->setAutoExit(false);
            $application->run($input, $output);

            echo "test 4";
        }
        catch(\RuntimeException $e) {
            outp($e);
            $this->write($e->getMessage());
        }
        catch(Exception $e) {
            outp($e);
            $this->write($e->getMessage());
        }
        echo "5";
        chdir("/home/vhosts/raymond2/public_html/cms4");
        echo "6." . getcwd();
        return "";
    }
}

?>