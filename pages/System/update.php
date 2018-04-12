<?php

class System_update extends ALT\Page
{

    public function getLastestVersion()
    {
        $v = $this->getVersionList();
        return $v[0];
    }

    public function getVersionList()
    {

        $composer = new App\Composer();

        $auth = $composer->auth();
        $username = $auth["http-basic"]["raymond2.hostlink.com.hk"]["username"];
        $password = $auth["http-basic"]["raymond2.hostlink.com.hk"]["password"];

        $context = stream_context_create(array(
            'http' => array(
                'header' => "Authorization: Basic " . base64_encode("$username:$password")
            )
        ));

        $repo = json_decode(file_get_contents("https://raymond2.hostlink.com.hk/bitbucket/repo/packages.json", false, $context), true);
        return array_reverse(array_keys($repo["packages"]["hostlink/r-alt"]));
    }

    public static function GetDirectorySize($path)
    {
        $bytestotal = 0;
        $path = realpath($path);
        if ($path !== false) {
            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object) {
                $bytestotal += $object->getSize();
            }
        }
        return $bytestotal;
    }

    public function globr($sDir, $sPattern, $nFlags = null)
    {
        $sDir = escapeshellcmd($sDir);
        $aFiles = glob("$sDir/$sPattern", $nFlags);
        foreach (glob("$sDir/*", GLOB_ONLYDIR) as $sSubDir) {
            $aSubFiles = globr($sSubDir, $sPattern, $nFlags);
            $aFiles = array_merge($aFiles, $aSubFiles);
        }
        return $aFiles;
    }

    public function getall()
    {
        API::output($this->globr(CMS_ROOT . file_get_contents(CMS_ROOT . "version"), "*"));
    }

    public function selectSystem($version)
    {
        //App\Composer::Check();
        //App\Composer::InstallSystemRequire();

//		if ($system) {
//			file_put_contents(CMS_ROOT . "/version", $system);
//		}
//		App::Redirect("System/update");

        App\Composer::Run("require hostlink/r-alt:$version");
        App::Msg("System updated");
        App::Redirect("System/update");
    }

    public function download($system, $plugins)
    {
        if ($system) {
            $version = $system;
            $source = App::Config("system", "update_source") . "$version";
            $local_file = getcwd() . "/system/{$version}";
            file_put_contents($local_file, file_get_contents($source));
            $name = substr($version, 0, -3);

            $new_system = CMS_ROOT . "/system/{$version}";
            // remove folder
            $folder = CMS_ROOT . "/system/" . $name;

            `rm -rf $folder`;
            // create empty folder
            mkdir($folder);
            // unzip it
            `tar -xvzf $new_system -C system/$name`;

            `touch {$folder}/include.inc.php`;

            `touch {$folder}/function/function.php`;
            // remove gz file
            unlink($new_system);
            App::Msg($folder . " created");
        } elseif ($plugins) {
            $name = substr($plugins, 0, -3);
            $path = CMS_ROOT . "/plugins/" . $name;
            $gz = CMS_ROOT . "/plugins/$plugins";

            file_put_contents($gz, file_get_contents(App::Config("system", "update_source") . "plugins/$plugins"));
            // remove lib folder
            `rm -rf $path`;
            // create empty folder
            mkdir($path);
            // unzip it
            `tar -xvzf $gz -C plugins/$name`;
            // chmod
            `find $path -type d -exec chmod 0777 {} +`;
            `find $path -type f -exec chmod 0777 {} +`;
            // remove gz file
            unlink($gz);
            App::Msg("$plugins installed");
        }
        App::Redirect("System/update");
    }

    public function deleteSystem($system)
    {
        if ($system) {
            $path = CMS_ROOT . "/system/" . $system;

            `rm -rf $path`;
            App::Msg($path . " removed");
        }

        App::Redirect("System/update");
    }

    public function deletePlugin($name)
    {
        if ($name) {
            $path = CMS_ROOT . "/plugins/" . $name;

            `rm -rf $path`;

            App::Msg($name . " removed");
        }
        App::Redirect("System/update");
    }

    public function getSystemPackage()
    {
        //$composer = file_get_contents(getcwd() . "/composer/composer.json");
        $core = [];
        $core[] = "hostlink/r-db";
        $core[] = "twig/twig";
        $core[] = "twig/extensions";
        $core[] = "phpmailer/phpmailer";
        $core[] = "bassjobsen/bootstrap-3-typeahead";
        $core[] = "components/font-awesome";
        $core[] = "driftyco/ionicons";
        $core[] = "robinherbots/jquery.inputmask";

        $data = [];
        foreach ($core as $c) {
            $data[] = json_decode(file_get_contents(getcwd() . "/composer/vendor/$c/composer.json"), true);
        }

        return $data;
    }

    public function componser($package)
    {
        App\Composer::Install($package);
        App::Redirect("System/update");
    }

    public function get()
    {
        //composer
        //outp($this->getVersionList());

        //$this->write($this->getVersionList());
        ///return;

        // check permission
        $root = CMS_ROOT;

        $p = App::Request("System/db_check");
        if ($p->needUpdate()) {
            $this->callout("System", "DB version updated, please update db.", "warning");
        }

//		if (My\File::_($root . "/system")->permission() != "0777") {
//			$this->callout("System", "In order to update, please change the permission of folder ({$root}/system) to 0777", "danger");
//		}

//		if (My\File::_($root . "/version")->permission() != "0777") {
//			$this->callout("System", "In order to update, please change the permission of file ({$root}/version) to 0777", "danger");
//		}

        if (My\File::_($root . "/plugins")->permission() != "0777") {
            $this->callout("System", "$root/plugins permission not equal 0777", "warning");
        }

        $this->navbar()->addButton("DB check", "System/db_check");

        $this->write("<h4>Use compose to update system: <a href='System/composer'>composer</a></h4>");
        $this->write("<h4>Your current system: " . App::Version() . "</h4>");
        $this->write("<h4>Lasted version: " . $this->getLastestVersion() . "</h4>");

/*		$t->add("Size", function ($path) {
			try {

				return self::GetDirectorySize($path); }
			catch (exception $e) {

			}
		}
		);

		$t->add("", function ($name) {
			$s = basename($name); if ($s != App::Version()) {
				return BS\Button::_()->href("System/update/deleteSystem?system=$s")->html("<i class='fa fa-times'></i>")->addClass('btn-danger')->
					addClass("btn-xs"); }
		}
		);

		$t->add("", function ($name) {
			$s = basename($name); return BS\Button::_()->href("System/update/selectSystem?system=$s")->html("<i class='fa fa-check'></i>")->
				addClass("btn-success")->addClass('confirm btn-xs'); }
		);

		$t->header("Local");
		$this->write($t);*/

        $path = App::Config("system", "update_source") . "get3.php";
        $source = json_decode(file_get_contents($path), true);

/*		$t = $this->createT($source);
		$t->header("Download new system");
		$t->body()->css("max-height", "250px");
		$t->add("Name", "name");
		$t->add("Size", 'size');
		$b = $t->add("Download")->button()->href(function ($obj) {
			return 'System/update/download?system=' . $obj["name"]; }
		)->addClass("btn-primary")->html("<i class='fa fa-download'></i>");

		$this->write($t);*/

        ///composer
/*		$t = $this->createT($this->getSystemPackage());
		$t->add("Name", "name");
		$t->add("Update")->button()->html("Update")->href(function ($obj) {
			return "System/update/composer?package=" . $obj["name"]; }
		)->addClass("btn-primary");

		$this->write($t);*/


        $path = App::Config("system", "update_source") . "plugins/get.php";
        $t = $this->createT(json_decode(file_get_contents($path), true));
        $t->header("Download new plugins");
        $t->add("Name", 'name');
        $t->add("Size", 'size');
        $t->add("Download")->button()->html("<i class='fa fa-download'></i>")->href(function ($obj) {
            return "System/update/download?plugins=" . $obj["name"];
        })->addClass("btn-primary");
        $this->write($t);

        $t = $this->createT(glob(CMS_ROOT . "/plugins/*"));
        $t->header("Installed plugins");
        $t->add("Name", function ($i) {
            return basename($i);
        });
        $t->add("Permission", function ($s) {
            return substr(sprintf('%o', fileperms($s)), -4);
        });
        $t->add("")->button()->html("<i class='fa fa-times'></i>")->href(function ($obj) {
            $s = basename($obj);
            return "System/update/deletePlugin?name=$s";
        })->addClass("btn-danger");
        $this->write($t);
    }
}
