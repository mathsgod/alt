<?php

class System_bower extends ALT\Page
{
    public function run($command)
    {
        putenv("HOME=" . getcwd() . "/bower");
        $phar = App::SystemPath("bower/bowerphp.phar");

        chdir($this->getPath());

        if ($command) {
            $ret = `php $phar $command 2>&1`;
        } else {
            $ret = `php $phar 2>&1`;
        }

        $this->write("<pre>$ret</pre>");
    }
    public function post()
    {
        $d = getcwd();

        putenv('HOME=' . getcwd() . "/bower");

        chdir("/home/vhosts/raymond2/public_html/cms4/system/source/bower");

        $cmd = ["command" => $_POST["command"]];

        if ($_POST["command"] == "search") {
            $cmd["name"] = $_POST["name"];
        }
        if ($_POST["package"]) {
            $cmd["package"] = $_POST["package"];
        }

        if ($_POST["command"] == "install") {
            $cmd["--save"] = true;
        }

        $input = new Symfony\Component\Console\Input\ArrayInput($cmd);

        $output = new Symfony\Component\Console\Output\BufferedOutput();
        // Create the application and run it with the commands
        try {
            $application = new Bowerphp\Console\Application();

            $application->setAutoExit(false);
            $application->run($input, $output);
        } catch (\RuntimeException $e) {
            $this->write($e->getMessage());
        } catch (Exception $e) {
            $this->write($e->getMessage());
        }
        $str = $output->fetch();

        $this->write("<pre>" . $str . "</pre>");
    }

    public function removeBower()
    {
        $folder = App::SystemPath("bower/bower_components");

        `rm -rf $folder`;
        return;
    }
    public function jsonFile()
    {
        $this->write("<pre>" . file_get_contents($this->getPath() . "/bower.json") . "</pre>");
    }

    public function getPath()
    {
        if ($this->session("system")) {
            return App::SystemPath("bower");
        } else {
            return getcwd() . "/bower";
        }
    }

    public function get($system = 0)
    {
        $this->session("system", $system);
        // check compser
        if (!is_readable($folder = $this->getPath())) {
            $this->callOut("Error", "bower folder {$folder} not found!", "danger");
            return;
        }

        $box = new ALT\Box();
        $box->header('Bower');
        $e = $this->createE();
        $e->add("command")->inputSelect("command")->ds(["install", "update", "list", "require", "list-command"])->attr('id', "command");
        $box->body()->append((string)$e);
        $box->footer()->append("<button class='btn btn-primary' onClick='onClickRun()'>Run</button>");
        $this->write($box);

        $box = new ALT\Box();
        $box->body();
        $box->attr("id", "result1");
        $box->header("Result");
        $this->write($box);
        $box = new ALT\Box();
        $box->attr("data-url", "System/bower/jsonFile");
        $this->write($box);
    }
}
