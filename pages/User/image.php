<?php
class User_image extends R\Page
{
    public function get($dummy)
    {
        $basepath = \App\System::BasePath();

        if ($dummy) {
            header("location: {$basepath}composer/vendor/hostlink/r-alt/images/user.png");
            return;
        }

        $path=$this->request->getUri()->getPath();
        $path=explode("/", $path);
        $user_id=$path[1];

        if (is_numeric($user_id)) {
            $user = new App\User($user_id);
        } else {
            $user = App::User();
        }

        //$this->setHeader("Content-Type","application/jpeg");
        if (file_exists($f = getcwd() . "/data/{$user->username}/profile.image")) {


            header("location: {$basepath}data/{$user->username}/profile.image");
        } else {
            header("location: {$basepath}composer/vendor/hostlink/r-alt/images/user.png");
        }
    }
}
