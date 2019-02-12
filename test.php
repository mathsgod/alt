<?php
error_reporting(E_ALL & ~E_NOTICE);
$loader = require_once(__DIR__ . "/vendor/autoload.php");
$app = new App\App(__DIR__, $loader);

$app->login("raymond", "222222");
print_r($app->user->isGuest());


