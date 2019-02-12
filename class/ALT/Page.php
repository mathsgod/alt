<?php

namespace ALT;

use R\Psr7\Request;
use R\Psr7\Response;
use R\Psr7\Stream;

class Page extends \App\Page
{
    private $header = [];
    public $callout;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->callout = new Callout();
    }

    public function addLib($name)
    {
        try {
            return parent::addLib($name);
        } catch (\Exception $e) {
            $this->callout->warning("Plugins", $e->getMessage());
        }
    }

    public function header($name, $description = null, $icon)
    {
        $this->header["name"] = $name;
        $this->header["description"] = $description;
        $this->header["icon"] = $icon;
    }

    public function breadcrumb()
    {
        $b = p("ol")->addClass('breadcrumb');
        $b->append('<li><a href=""><i class="fa fa-home"></i> ' . $this->translate('Home') . '</a></li>');
        $b->append("<li><a href='{$this->module()->name}'>" . $this->translate($this->module()->name) .
            "</a></li>");
        return $b;
    }


    private $_navbar;
    public function navbar()
    {
        if (!$this->_navbar) {
            $this->_navbar = new Navbar($this);
        }
        return $this->_navbar;
    }

    public function __invoke(Request $request, Response $response)
    {
        $action = $request->getAttribute("action");
        $this->request = $request;
        if ($request->getMethod() == "get") {
            if ($action == "index") {
                $this->app->savePlace();
            } elseif ($action == "v") {
                $obj = $this->object();
                if (is_a($obj, "App\Model")) {
                    if (!$obj->canRead()) {
                        return $this->app->accessDeny($request);
                    }
                    if ($obj->canUpdate() && $this->module()->show_update) {
                        $this->navbar()->addButton("", $obj->uri("ae"))->icon("fa fa-pencil-alt")->addClass("btn-warning");
                    }
                    if ($obj->canDelete() && $this->module()->show_delete) {
                        $this->navbar()->addButton("", $obj->uri("del") . "?redirect=" . $this->module()->name)->addClass("btn-danger confirm")->icon("fa fa-times");
                    }
                }
                $this->app->savePlace();
            } elseif ($action == "ae") {
                if ($this->id()) {
                    if (!\App\ACL::Allow($this->module()->class, "U")) {
                        return $this->app->accessDeny($request);
                    }
                } else {
                    if (!\App\ACL::Allow($this->module()->class, "C")) {
                        return $this->app->accessDeny($request);
                    }
                }
            }
        }

        if ($request->isAccept("text/html") && $request->getMethod() == "get") {
            $this->master = new MasterPage($this->app);
            $this->header["name"] = $this->module()->name;
        }

        $response = parent::__invoke($request, $response);

        if ($request->isAccept("application/json")) {
            if ($request->getMethod() == "get" && $action == "index") {
                return $response;
            }
        }

        if ($request->getMethod() == "post") {
            return $response;
        }

        if ($request->getHeader("X-Requested-With")) {
            return $response;
        }


        if ($request->isAccept("text/html") || $request->isAccept("*/*")) {
            if ($this->request->getMethod() == "get" && $this->master) {

                $this->addLib("json-viewer");
                //$this->addLib("components/moment");
                //$this->addLib("bootboxjs");
                //$this->addLib("jquery-ui");
//                $this->addLib("twbs/bootstrap");
                //$this->addLib("components/bootstrap-datepicker");
                //$this->addLib("bootstrap-colorpicker");
                $this->addLib("i18next");
                $this->addLib("components/bootstrap-datetimepicker");

                $this->addLib("jquery-validation");
                $this->addLib("purl");

                //$this->addLib("hostlink/fancybox");
                //$this->addLib("daterangepicker");
                //$this->addLib("pnotify");
                //$this->addLib('iCheck');

                $this->addLib("timepicker");
                //$this->addLib("select2");
                $this->addLib("bootstrap-wysihtml5");
                //$this->addLib("bootstrap-multiselect");
                // $this->addLib("touchPoint");
                $this->addLib("slimScroll");
                $this->addLib("fastclick");

                $this->addLib("robinherbots/jquery.inputmask");
                $this->addLib("json-viewer");

                $this->addLib("bassjobsen/bootstrap-3-typeahead");
                //$this->addLib("bootstrap-select/bootstrap-select");
                //$this->addLib("DataTables");
                $this->addLib("ace");

                $data = [];
                $data["title"] = $this->module()->name;

                if (count($this->header)) {
                    $header = [];
                    $header["title"] = $this->header["title"];
                    $header["name"] = $this->translate($this->header["name"]);
                    $header["icon"] = $this->header["icon"];
                    $header["description"] = $this->header["description"];
                    $header["breadcrumb"] = $this->breadcrumb();


                    if ($this->_navbar) {
                        $app = $this->app;
                        $this->_navbar->find("a")->each(function ($i, $o) use ($app) {
                            $o = p($o);
                            if (!$app->acl($o->attr("href"))) {
                                $o->remove();
                            }
                        });
                        if ($this->_navbar->find("a")->size() || $this->_navbar->find("button")->size()) {
                            $data["navbar"] = (string )$this->_navbar;
                        }
                    }
                }

                $data["header"] = $header;

                foreach ($this->_lib as $name => $p) {
                    foreach ($p->jss(\My::Language()) as $js) {
                        $data["script"][] = $js;
                    }

                    foreach ($p->csss() as $c) {
                        $data["css"][] = $c;
                    }

                    foreach ($p->setting["import"] as $import) {
                        $f = $p->base . "/" . $import;
                        $data["css"][] = $f;
                    }
                }

                $plugins = new \App\Plugin("Sortable");

                $data["jquery"] = $plugins->jss();

                extract(\App::_()->pathInfo());
                $system = $system_base;

                $data["system_base"] = $system_base;


                $plugins = new \App\Plugin("vue");
                $data["vue"] = $plugins->jss();

                $data["jss"][] = "$system/dist/moment/moment-with-locales.min.js";
                $data["jss"][] = "$system/dist/fullcalendar/fullcalendar.min.js";
                if ($this->app->locale != "en") {
                    $data["jss"][] = "$system/dist/fullcalendar/locale/" . $this->app->locale . ".js";
                }



                $data["script"][] = "$system/AdminLTE/dist/js/app.js";

                $data["script"][] = "$system/js/jquery.datetimepicker.js";
                $data["script"][] = "$system/js/jquery.form-step.js";
                $data["script"][] = "$system/js/jquery.form-create.js";

                $data["script"][] = "$system/js/jquery.table.js";
                $data["script"][] = "$system/js/jquery.preview.js";
                $data["script"][] = "$system/js/jquery.form.js";
                $data["script"][] = "$system/js/jquery.timer.js";
                $data["script"][] = "$system/js/jquery.ajaxbox.js";
                $data["script"][] = "$system/js/jquery.box.js";


                //$data["script"][] = "$system/js/vue.img-box.js";


                //$data["script"][] = "$system/js/layout.js";
                $data["script"][] = "$system/js/default.js";


                foreach (glob(getcwd() . "/js/*") as $file) {
                    $data["script"][] = "js/" . basename($file);
                }

                $path_info = \App::_()->pathInfo();
                if (file_exists($path_info["cms_root"] . "/pages/" . $this->path() . ".js")) {
                    $data["script"][] = "pages/" . $this->path() . ".js";
                }

                if (file_exists($path_info["system_root"] . "/pages/" . $this->path() . ".js")) {
                    $data["script"][] = $path_info["system_base"] . "/pages/" . $this->path() . ".js";
                }


                
                /*if(file_exists()){

                }
                outP($path_info)
                outP($this->path());*/



                $data["content"] .= $echo_content;
                $data["content"] .= (string)$response;


                $data["css"][] = "$system/AdminLTE/dist/css/AdminLTE.css";
                $data["css"][] = "$system/AdminLTE/dist/css/skins/_all-skins.min.css";




                $data["css"][] = "$system/css/default.css";

                if (is_readable("themes/global.css")) {
                    $data["css"][] = "themes/global.css";
                }

                if (($skin = \App::User()->skin()) instanceof Skin) {
                    // user skin
                    foreach ($skin->css() as $css) {
                        $data["css"][] = $css;
                    }
                } else {
                    // user skin
                    if (is_readable($css = "themes/" . \App::User()->skin() . ".css")) {
                        $data["css"][] = $css;
                    }
                }
                // callout
                $data["callouts"] = $this->callout;

                foreach ($data as $k => $v) {
                    $this->master->assign($k, $v);
                }

                $headers = $response->getHeaders();
                $response = new Response(200);
                foreach ($headers as $name => $value) {
                    foreach ($value as $v) {
                        $response = $response->withHeader($name, $v);
                    }
                }

                $request = $request->withAttribute("module", $this->module());
                return $this->master->__invoke($request, $response);
                
                //return $response->withBody(new Stream($this->master_tpl($request,$response)));
            } else {
                return $response;
            }
        }
    }

    public function createBox($body = null)
    {
        $box = new \App\UI\Box($this);
        $box->classList->add("box-primary");
        if ($body) {
            $box->body()->append($body);
        }
        return $box;
    }

    public function createE($object = null)
    {
        if (func_num_args() == 0) {
            $object = $this->object();
        }
        return new \App\UI\E($object, $this);
    }

    public function createGrid($sizes)
    {
        $route = $this->request->getAttribute("route");
        $action = $route->action;

        $grid = new Grid();
        $uri = $this->module()->name . "/" . $action . "/grid[" . $grid->attr('grid-num') . "]";
        $grid->attr("data-uri", $uri);
        // load layout
        $ui = \App\UI::_($uri);
        if ($ui->layout) {
            $grid->layout = json_decode($ui->layout, true);
        }

        foreach ($sizes as $s) {
            $row = $grid->addRow();
            foreach (range(1, $s) as $a) {
                $col = floor(12 / $s);
                $section = p("section");
                $section->attr("is", "alt-grid-section");
                $section->addClass("col-md-$col ui-sortable connectedSortable");
                $row->append($section);
            }
        }
        return $grid;
    }
}
