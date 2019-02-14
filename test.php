<?php
error_reporting(E_ALL & ~E_NOTICE);
$loader = require_once(__DIR__ . "/vendor/autoload.php");
$app = new App\App(__DIR__, $loader);

//$app->login("raymond", "222222");
foreach (App\User::Query() as $u) {
    outp($u);
}

return;
$app->user->UserList->where([
    "usergroup_id" => $usergroup->usergroup_id
])->delete();

return;
$q = $app->user->UserList;

$q->where(["usergroup_id" => 1]);
$q->delete();



