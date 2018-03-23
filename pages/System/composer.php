<?php
use App\Composer;

class System_composer extends ALT\Page
{

    public function run($command)
    {
        $composer = new App\Composer();
        $ret = $composer->exec($command);
        $this->write("<pre>$ret</pre>");
    }

    public function jsonFile()
    {
        $composer = new App\Composer();
        $this->write("<pre>" . json_encode($composer->config(), 255) . "</pre>");
    }

    public function info($checkupdate)
    {

        $composer = new App\Composer();
        $package = $composer->info($checkupdate);

        $installed = array_map(function ($o) {
            return $o["name"];
        }, $package);

        $pre_define = $composer->suggests();
        foreach ($pre_define as $k) {
            if (!in_array($k, $installed)) {
                $package[] = ["name" => $k];
            }
        }

        return $package;
    }

    public function get($cmd)
    {

        $composer = new App\Composer();

        $this->navBar()->addButton("Check update", "System/composer?cmd=check_update");
        $this->navBar()->addButton("Add Hostlink repo", "System/composer?cmd=add_repo");

        if ($cmd == "add_repo") {
            $composer->exec("config repositories.hostlink-raymond composer https://raymond2.hostlink.com.hk/bitbucket/repo");
        }

        
        //App\Composer::ChangeOwn();

        $t = $this->createT($this->info($cmd == 'check_update'));
        $t->header()->addButton("Package list")->addClass("btn-primary")
            ->href("https://packagist.org/")->attributes["target"] = "_blank";

        $t->add("Name", "name");
        $t->add("Description", "description");
        $t->add("Version", "version");

        if ($cmd == "check_update") {
            $t->add("Latest version", "latest");
            $t->add("Status", "latest-status");
        }

        $t->add("", function ($o) {
            $name = $o["name"];
            $html = [];
            if (($o["latest-status"] == "update-possible") || ($o["latest-status"] == "semver-safe-update")) {
                $html[] = "<button class='btn btn-primary btn-xs' onClick='run(\"update {$name}\")' ><i class='fa fa-arrow-up'></i> Upgrade</button>";
            }

            if ($o["version"]) {
                $html[] = "<button class='btn btn-danger btn-xs' confirm onClick='run(\"remove {$name}\")' ><i class='fa fa-times'></i></button>";
            } else {
                $html[] = "<button class='btn btn-success btn-xs' onClick='run(\"require {$name}\")' ><i class='fa fa-download'></i></button>";
            }
            return implode("", $html);
        });

        $this->write($t);

        $box = new ALT\Box();
        $box->header('Composer');
        $e = $this->createE(null);
        $e->add("command")->inputSelect("command")->ds(["install", "update", "list", "require", "remove", "clearcache"])->attr(
            'id',
            "command"
        );
        $box->body()->append($e);
        $box->footer()->append("<button class='btn btn-primary' onClick='onClickRun()'>Run</button>");
        $this->write($box);

        $box = new ALT\Box();
        $box->body();
        p($box)->attr("id", "result1");
        $box->header("Result");
        $this->write($box);
        $box = new ALT\Box();
        p($box)->attr("data-url", "System/composer/jsonFile");
        $this->write($box);


        //reset htaccess
        $this->reset_htaccess();
    }

    public function reset_htaccess()
    {
        $composer = new App\Composer();
        unlink($composer->path() . "/.htaccess");
        $content = <<<EOL
<Files ~ "\.(css|js|jpg|png|woff2|woff|ttf|html)$">
Allow from all
</Files>
Deny from all
EOL;
        file_put_contents($composer->path() . "/.htaccess", $content);

    }
}
