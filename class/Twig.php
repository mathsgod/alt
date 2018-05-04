<?php

class Twig
{
    public $environment;
    public $loader;

    public function render($data = [])
    {
        return $this->template->render($data);
    }

    public static function _($template_file)
    {
        $path = $template_file;

        $pi = pathinfo($path);


        $twig = new \Twig;
        $twig->loader = new \Twig_Loader_Filesystem(pathinfo($path, PATHINFO_DIRNAME));
        $twig->environment = new \Twig_Environment($twig->loader);
        $twig->template = $twig->environment->loadTemplate($pi["basename"]);

        return $twig;
    }
}
