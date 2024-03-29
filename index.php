<?php
date_default_timezone_set('Asia/Hong_Kong');
ini_set("display_errors", "On");
error_reporting(E_ALL && ~E_WARNING);
setlocale(LC_ALL, 'en_US.UTF-8'); //do not remove

$loader = require_once(__DIR__ . "/vendor/autoload.php");

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
session_start();
//$log = new Logger('App');
//$log->pushHandler(new StreamHandler(__DIR__ . '/logs/' . date("Y-m-d") . ".log", Logger::DEBUG));

$app = new App\App(__DIR__, $loader);
$app->run();
