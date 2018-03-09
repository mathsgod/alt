<?php
namespace App;

class FormBuilder extends \App\Model
{
    public $_page;
    public function setPage($page)
    {
        $this->_page=$page;
        return $this;
    }
    public static function _($name)
    {
        $w=[];
        $w[]=["name=?",$name];
        if (!$o=FormBuilder::First($w)) {
            $o=new FormBuilder();
            $o->name=$name;
        }
        
        return $o;
    }


    public $_control=[];

    public function get($name)
    {
        return $this->_control["name"];
    }

    public function __toString()
    {

        $html="";
        if (\App::User()->isAdmin()) {
            $html.=(string)html("a")->class('btn btn-xs btn-warning')->href($this->uri("ae"))->html("<i class='fa fa-pencil'></i> Form builder");
        }

        return $html.$this->_output;
    }

    public function renderV($obj)
    {
        $v=$this->_page->createV($obj);
        $this->render($v);
        
        return $this;
    }

    public function renderE($obj)
    {
        $v=$this->_page->createE($obj);
        
        $this->render($v);
        
        return $this;
    }
    
    public function render($view)
    {
        $this->_control=[];
             
        $content=json_decode($this->content, true);
        foreach ($content as $component) {
            //check role
            if ($component["role"]) {
                $role=explode(",", $component["role"]);
                if (!\App::User()->isOneOf($role)) {
                    continue;
                }
            }
            if ($component["type"]=="p") {
                $row=$view->add($component["label"], $component["index"]);
            } elseif ($component["type"]=="header") {
                $p=$view->addHeader($component["subtype"]);
                if ($component["className"]) {
                    $p->addClass($component["className"]);
                }
                $p->html($component["label"]);
            } elseif ($component["type"]=="paragraph") {
                $p=$view->addParagraph();
                $subtype=$component["subtype"];
                $n=p($subtype);
                if ($component["className"]) {
                    $n->addClass($component["className"]);
                }
                $n->html($component["label"]);
                $p->html((string)$n);
            } else {
                $row=$view->add($component["label"]);
                if ($component["type"]=="text") {
                    if ($component["subtype"]=="text") {
                        $ctrl=$row->input($component["name"]);
                    } elseif ($component["subtype"]=="email") {
                        $ctrl=$row->email($component["name"]);
                    } elseif ($component["subtype"]=="password") {
                        $ctrl=$row->input($component["name"]);
                        $ctrl->attr("type", "password");
                    }
            
                    if ($component["required"]) {
                        $ctrl->required();
                    }
                    if ($component["placeholder"]) {
                        $ctrl->attr("placeHolder", $component["placeholder"]);
                    }
                } elseif ($component["type"]=="select") {
                    $ctrl=$row->select($component["name"]);
                    if ($component["className"]) {
                        $ctrl->addClass($component["className"]);
                    }
                    if ($component["multiple"]) {
                        $ctrl->attr("multiple", true);
                    }
                    if ($component["source"]) {
                        $ctrl->ds(SystemValue::_($component["source"])->values());
                        if ($component["required"]) {
                            $ctrl->attr("required", true);
        
                            if ($component["placeholder"]) {
                                $option=html("option")->disabled(true)->html($component["placeholder"]);
                                $ctrl->prepend((string)$option);
                            }
                        }
                        if (!$component["required"]) {
                            $ctrl->prepend("<option/>");
                        }
                    } else {
                        if ($component["values"]) {
                            $options=[];
                            foreach ($component["values"] as $opt) {
                                $options[$opt["value"]]=$opt["label"];
                            }
                            $ctrl->ds($options);
                        }
                    }
                } elseif ($component["type"]=="textarea") {
                    $textarea=$row->textarea($component["name"]);
                } elseif ($component["type"]=="date") {
                    $row->date($component["name"]);
                } elseif ($component["type"]=="file") {
                    $input=$row->input($component["name"])->attr("type", "file");
                    if ($component["multiple"]) {
                        $input->attr("multiple", true);
                    }
                } elseif ($component["type"]=="ckeditor") {
                    $ctrl=$row->textarea($component["name"]);
                    $ctrl->addClass("ckeditor");
                }
            
                if ($component["description"]) {
                    $row->helpBlock($component["description"]);
                }
            }
        
            $this->_control[$component["name"]]=$ctrl;
        }
        
        $this->_output=$view;
        return $view;
    }
}
