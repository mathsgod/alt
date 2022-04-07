<?php

namespace ALT\Page;

use R\Psr7\Stream;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Login extends \R\Page
{
    private $_lib = [];
    private $_template;
    private $_twig;

    public function addLib($name)
    {
        $p = new \App\Plugin($name);

        foreach ($p->setting["require"] as $require) {
            $this->addLib($require);
        }

        foreach ($p->setting["php"] as $php) {
            include_once($p->base . "/" . $php);
        }

        $this->_lib[$name] = $p;
        return $p;
    }


    public function __invoke($request, $response)
    {

        $resp = parent::__invoke($request, $response);

        if ($request->getMethod() == "get") {
            $pi = $this->app->pathInfo();
            $this->_twig["loader"] = new  FilesystemLoader($pi["system_root"]);
            $env = new Environment($this->_twig["loader"]);;
            $this->_twig["environment"] = $env;

            $this->_template = $env->load("AdminLTE/pages/login.html");
        }

        $data = $resp->getBody()->getContents();
        $data = json_decode($data, true);

        if ($this->_template) {
            if (!$data) {
                $data = [];
            }

            $p = new \App\Plugin("vue");
            $data["vue"] = $p;

            foreach ($this->_lib as $name => $p) {
                foreach ($p->setting["css"] as $css_f) {
                    $f = $p->base . "/" . $css_f;
                    $data["css"][] = $f;
                }

                foreach ($p->setting["js"] as $js_f) {
                    $f = $p->base . "/" . $js_f;
                    $data["script"][] = $f;
                }
            }

            $p = \App::_()->pathInfo();

            $data["css"][] = $p["system_base"] . "/AdminLTE/dist/css/AdminLTE.min.css";

            $data["script"][] = $p["system_base"] . "/js/jquery.form.min.js";

            $resp = $resp
                ->withHeader("Content-Type", "text/html; charset=UTF-8")
                ->withBody(new Stream($this->_template->render($data)));
        }

        return $resp;
    }
}
