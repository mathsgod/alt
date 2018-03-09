<?php

class System_tar extends ALT\Page
{
    public function generatePackageJson($composer)
    {
        //get packages.json
        $package_file = dirname(getcwd()) . "/repo/packages.json";
        $package = json_decode(file_get_contents($package_file), true);

        $version = $composer["version"];
        $name = $composer["name"];

        $package["packages"][$composer["name"]][$composer["version"]]["dist"] = ["type" => "zip", "url" => "https://raymond2.hostlink.com.hk/packages/{$name}-{$version}.zip"];

        foreach ($composer as $k => $v) {
            $package["packages"][$composer["name"]][$composer["version"]][$k] = $v;
        }

        file_put_contents($package_file, json_encode($package, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function post()
    {
        $cwd = getcwd();

        $composer = json_decode(file_get_contents(getcwd() . "/system/composer.json"), true);
        $composer["version"] = $_POST["version"];
        file_put_contents(getcwd() . "/system/composer.json", json_encode($composer, JSON_PRETTY_PRINT));

        $package = getcwd() . "/system";
        $version = $composer["version"];
        $name = $composer["name"];
        mkdir(dirname(getcwd()) . "/packages/hostlink");
        $target = dirname(getcwd()) . "/packages/{$name}-{$version}.zip";
        chdir($package);
        unlink($target);
        `zip -r $target .`;


        chdir($cwd);

        $this->generatePackageJson($composer);

        App::Msg("System source " . $_POST["version"] . " updated");
        App::Redirect("System/tar");
    }

    public function get()
    {
        $mv = $this->createE([
            "version" => date("4.6.ymd.1")
        ]);
        $mv->add("Version")->input("version")->required();
        $this->write($this->createForm($mv));
    }
}
