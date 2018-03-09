<?php

namespace App;

class Composer
{
    public function installed()
    {
        $ret=self::exec("show -f json");
        return json_decode($ret, true)["installed"];
    }

    public function show()
    {
        return self::exec("show -f json");
    }

    public function package($name)
    {
        foreach ($this->installed() as $p) {
            if ($p["name"]==$name) {
                $package=new Package();
                $package->name=$p["name"];
                $package->version=$p["version"];
                $package->description=$p["description"];
                $package->path=getcwd()."/composer/vender/".$name;

                return $package;
            }
        }
    }

    public function exec($command)
    {
        putenv("COMPOSER_HOME=" . $this->path());
        $phar = self::Phar();

        chdir("composer");

        if ($command) {
            $ret = `php $phar $command 2>&1`;
        } else {
            $ret = `php $phar 2>&1`;
        }

        chdir("..");

        return $ret;
    }
    
    public function hasPackage($package)
    {
        foreach ($this->installed() as $p) {
            if ($p["name"]==$package) {
                return true;
            }
        }
        return false;
    }

    public function path()
    {
        return getcwd()."/composer";
    }

    public static function PackageSuggest($package)
    {
        $suggest=self::Run("suggests $package");
        $suggest=explode("\n", $suggest);
        array_walk($suggest, "trim");
        $suggest=array_filter($suggest, "strlen");
        return $suggest;
    }

    public static function Auth()
    {
        return json_decode(file_get_contents(getcwd()."/composer/auth.json"), true);
    }

    public static function Suggests()
    {
        $suggest=self::Run("suggests");
        $suggest=explode("\n", $suggest);
        array_walk($suggest, "trim");
        $suggest=array_filter($suggest, "strlen");
        return $suggest;
    }

    public static function Info($checkupdate = false)
    {
        if ($checkupdate) {
            $info=self::Run("show -l --format=json");
        } else {
            $info=self::Run("show --format=json");
        }
        
        return json_decode($info, true)["installed"];
    }


    public static function Phar()
    {
        if (!file_exists($file = getcwd() . "/composer/composer.phar")) {
            file_put_contents($file, fopen("https://getcomposer.org/composer.phar", 'r'));
        }
        return $file;
    }

    public static function ChangeOwn()
    {
        $folder = getcwd() . "/composer";
        `find $folder -type d -exec chmod 0777 {} +`;
        `find $folder -type f -exec chmod 0777 {} +`;
    }

    public static function RemoveAll()
    {
        $folder = getcwd() . "/composer";
        `rm -rf $folder`;
    }

    public static function Check()
    {
        if (!is_readable($folder = getcwd() . "/composer")) {
            throw new \Exception("composer folder {$folder} not found!");
        }

        if (!is_writable($folder = getcwd() . "/composer")) {
            throw new \Exception("composer folder {$folder} not writable!");
        }


        if (!is_readable($file = getcwd() . "/composer/composer.phar")) {
            file_put_contents($file, fopen("https://getcomposer.org/composer.phar", 'r'));
        }
    }

    public static function Run($command)
    {
        putenv("COMPOSER_HOME=" . getcwd() . "/composer");
        $phar = self::Phar();
        chdir("composer");

        if ($command) {
            $ret = `php $phar $command 2>&1`;
        } else {
            $ret = `php $phar 2>&1`;
        }

        chdir("..");

        return $ret;
    }

    public static function Remove($package)
    {
        $ret = self::Run("remove $package");
        return $ret;
    }

    public static function Install($package, $version)
    {
        //check install
        $config = self::Config();

        if ($config["require"][$package]) {
            return;
        }

        $ret = self::Run("require $package");
        self::ChangeOwn();

        return $ret;
    }
}
