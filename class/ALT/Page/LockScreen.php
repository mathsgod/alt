<?php
namespace ALT\Page;

use R\Psr7\Stream;

class LockScreen extends \R\Page
{
    private $_lib = [];
    private $_template;
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
        $app=$this->app;
        if ($request->getMethod() == "get") {
            
            $this->_template=\Twig::_($app->getFile("AdminLTE/pages/lockscreen.html"));
        }

        $response = parent::__invoke($request, $response);

        if ($this->_template) {
            $data = [];
            $data = array_merge($response->getBody()->getContents(), $data);


            $p = new \App\Plugin("components/jquery");
            $data["jquery"] = $p->jss();

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

            $data["script"][] = $p["system_base"] . "/js/cookie.js";
            $data["script"][] = $p["system_base"] . "/js/jquery.form.min.js";

            $content = $this->_template->render($data);

            return $response
                ->withHeader("Content-Type", "text/html; charset=UTF-8")->withBody(new Stream($content));
        }

        return $response;
    }
}
