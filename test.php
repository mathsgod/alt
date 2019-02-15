<?php
error_reporting(E_ALL & ~E_NOTICE);
$loader = require_once(__DIR__ . "/vendor/autoload.php");
$app = new App\App(__DIR__, $loader);