<?php
use App\Composer;

class System_composer extends ALT\Page
{
    public function updateComposer(){
        $composer = new App\Composer();
        $composer->exec("self-update");
        return [true];
    }

    public function addRaymondRepo()
    {
        $composer = new App\Composer();
        $composer->exec("config repositories.hostlink-raymond composer https://raymond2.hostlink.com.hk/bitbucket/repo");
        return [true];
    }

    public function changeOwner()
    {
        $composer = new App\Composer();
        $composer->changeOwn();
        return [true];
    }

    public function run()
    {
        $composer = new App\Composer();
        $ret = $composer->exec($_POST["cmd"]);

        unlink($composer->path() . "/.htaccess");
        $content = <<<EOL
<Files ~ "\.(css|js|jpg|png|woff2|woff|ttf|html)$">
Allow from all
</Files>
Deny from all
EOL;
        file_put_contents($composer->path() . "/.htaccess", $content);

        return ["output" => $ret];
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

    public function installedPackages()
    {
        $composer = new App\Composer();
        return $this->info();
    }

}
