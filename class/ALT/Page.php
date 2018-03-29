<?php

namespace ALT;

use TemplatePower;
use R\Psr7\Response;
use R\Psr7\Stream;

class Page extends \App\Page
{
    private $matser_tpl;
    private $callout = [];
    private $header = [];

    public function addLib($name)
    {
        try {
            return parent::addLib($name);
        } catch (\Exception $e) {
            $this->callout("Plugins", $e->getMessage(), "warning");
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
        $b->append('<li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>');
        $b->append("<li><a href='{$this->module()->name}'>" . $this->translate($this->module()->name) .
            "</a></li>");
        return $b;
    }

    public function callout($title, $description = null, $type = "info")
    {
        $this->callout[] = ["title" => $title, "description" => $description, "type" => $type];
    }

    public function createTab($prefix)
    {
        return new Tab($this, $prefix);
    }

    private $_navbar;
    public function navbar()
    {
        if (!$this->_navbar) {
            $this->_navbar = new Navbar($this);
        }
        return $this->_navbar;
    }

    public function __invoke($request, $response)
    {
        $action=$request->getAttribute("action");
        $this->request=$request;
        if ($request->getMethod()=="get") {
            if ($action=="index") {
                \App::SavePlace();
            } elseif ($action=="v") {
                $obj=$this->object();
                
                if (is_a($obj, "App\Model")) {
                    if (!$obj->canRead()) {
                        \App::AccessDeny();
                        return;
                    }
                    if ($obj->canUpdate() && $this->module()->show_update) {
                        $this->navbar()->addButton("", $obj->uri("ae"))->icon("fa fa-pencil-alt")->addClass("btn-warning");
                    }
                    if ($obj->canDelete() && $this->module()->show_delete) {
                        $this->navbar()->addButton("", $obj->uri("del") . "?redirect=" . $this->module()->name)->addClass("btn-danger confirm")->
                            icon("fa fa-times");
                    }
                }
                \App::SavePlace();
            } elseif ($action=="ae") {
                if ($this->id()) {
                    if (!\App\ACL::Allow($this->module()->class, "U")) {
                        \App::AccessDeny();
                        return;
                    }
                } else {
                    if (!\App\ACL::Allow($this->module()->class, "C")) {
                        \App::AccessDeny();
                        return;
                    }
                }
            }
        }

        if ($request->isAccept("text/html") && $request->getMethod() == "get") {
            $this->master = new MasterPage();
            $this->header["name"]=$this->module()->name;
        }

        $response=parent::__invoke($request, $response);
        
        if ($request->isAccept("application/json")) {
            if ($request->getMethod() == "get" && $action == "index") {
                return $response;
            }
        }

        if ($request->getMethod()=="post") {
            return $response;
        }

        if ($request->getHeader("X-Requested-With")) {
            return $response;
        }


        if ($request->isAccept("text/html") || $request->isAccept("*/*")) {
            if ($this->request->getMethod() == "get" && $this->master) {

                $this->addLib("json-viewer");
                $this->addLib("bootboxjs");
                //$this->addLib("jquery-ui");
                $this->addLib("twbs/bootstrap");
                $this->addLib("components/bootstrap-datepicker");
                $this->addLib("bootstrap-colorpicker");
                $this->addLib("i18next");
                $this->addLib("components/bootstrap-datetimepicker");

                $this->addLib("components/font-awesome");
                $this->addLib("driftyco/ionicons");

                $this->addLib("jquery-validation");
                $this->addLib("purl");

                $this->addLib("fancyBox");
                $this->addLib("daterangepicker");
                $this->addLib("pnotify");
                $this->addLib('iCheck');
                // $this->addLib("polymer");
                $this->addLib("bootstrap3-editable");
                $this->addLib("timepicker");
                $this->addLib("select2");
                $this->addLib("bootstrap-wysihtml5");
                $this->addLib("bootstrap-multiselect");
                // $this->addLib("touchPoint");
                $this->addLib("slimScroll");
                $this->addLib("fastclick");

                $this->addLib("robinherbots/jquery.inputmask");
                $this->addLib("json-viewer");


                $this->addLib("bassjobsen/bootstrap-3-typeahead");
                $this->addLib("bootstrap-iconpicker");
                $this->addLib("bootstrap-select/bootstrap-select");

                
                try{
                    new \App\Plugin("datatables/datatables");
                    $this->addLib("datatables/datatables");
                }catch(\Exception $e){
                    
                }

                $data=[];
                $data["title"]=$this->module()->name;

                if (count($this->header)) {
                    $header = [];
                    $header["title"]=$this->header["title"];
                    $header["name"] = $this->translate($this->header["name"]);
                    $header["icon"] = $this->header["icon"];
                    $header["description"] = $this->header["description"];
                    $header["breadcrumb"] = $this->breadcrumb();

                    if ($this->_navbar) {
                        $this->_navbar->find("a")->each(function ($i, $o) {
                            $o = p($o);
                            if (!\App::ACL($o->attr("href"))) {
                                $o->remove();
                            }
                        }
                        );
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
                // jquery
                $plugins = new \App\Plugin("components/jquery");
                $data["jquery"] = $plugins->jss();

                //jquery ui
                $jqueryui=new \App\Plugin("jquery-ui");
                $data["jquery"]=\array_merge($data["jquery"],$jqueryui->jss());
                foreach($jqueryui->csss() as $css){
                    $data["css"][]=$css;
                }

                $plugins=new \App\Plugin("Sortable");
                $data["jquery"]=array_merge($data["jquery"],$plugins->jss());



                extract(\App::_()->pathInfo());
                $system=$system_base;

                $data["system_base"]=$system_base;

                
                $plugins = new \App\Plugin("vue");
                $data["vue"] = $plugins->jss();

                /*$data["vue"][]= "$system/js/vue.sortable.js";
                $data["vue"][] = "$system/js/vue.draggable.js";*/


                

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


                $data["script"][] = "$system/js/vue.img-box.js";

                
                $data["script"][] = "$system/js/layout.js";
                $data["script"][] = "$system/js/default.js";


                foreach (glob(getcwd() . "/js/*") as $file) {
                    $data["script"][] = "js/" . basename($file);
                }

                $plugins = new \App\Plugin("angular");
                $data["angular"] = $plugins->jss();

                
                $data["content"].= $echo_content;
                $data["content"].= (string)$response;
                
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
           
        
                $response=new Response(200);
                $request=$request->withAttribute("module", $this->module());
                return $this->master->__invoke($request, $response);
                
                //return $response->withBody(new Stream($this->master_tpl($request,$response)));
            } else {
                return $response;
            }
        }
    }

    public function createBox()
    {
        $box = new Box($this);
        $box->classList->add("box-primary");
        return $box;
    }

    public function createV($object)
    {
        if (!$object) {
            $object = $this->object();
        }
        return new V($object, $this);
    }

    public function createE($object)
    {
        if (func_num_args() == 0) {
            $object = $this->object();
        }
        return new \App\UI\E($object, $this);
    }

    public function createT($objects)
    {
        return new T($objects, $this);
    }

    public function createGrid($sizes)
    {
        $route=$this->request->getAttribute("route");
        $action=$route->action;

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
                $section->addClass("col-md-$col ui-sortable connectedSortable");
                $row->append($section);
            }
        }
        return $grid;
    }

    public function creatorBox($object)
    {
        if (!$object) {
            $object = $this->object();
        }
        $v = $this->createV($object);
        if (property_exists($object, "created_by")) {
            $v->add("Created by", "createdBy()");
        }
        if (property_exists($object, "created_time")) {
            $v->add("Created time", "created_time");
        }
        if (property_exists($object, "updated_by")) {
            $v->add("Updated by", "updatedBy()");
        }
        if (property_exists($object, "updated_time")) {
            $v->add("Updated time", "updated_time");
        }
        $v->header("Creator information");
        return $v;
    }
}
