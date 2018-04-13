<?php
namespace ALT;

// class T extends \App\UI\T {
class T extends Box
{
    public $objects;
    public $table;

    public function __construct($objects, $route)
    {
        parent::__construct($route);
        $this->objects = $objects;
        $this->body()->addClass('no-padding table-responsive');
        $this->classList->add("box-primary");
        $this->table = new \App\UI\T($objects, $route);
        $this->body()->append($this->table);
    }

    public function addCheckbox($index)
    {
        return $this->table->addCheckbox($index);
    }

    public function add($label, $getter)
    {
        return $this->table->add($label, $getter);
    }
    
    public function addChildRow($label, $getter)
    {
        return $this->table->addChildRow($label, $getter);
    }

    public function addEdit()
    {
        return $this->table->addEdit();
    }

    public function addDel()
    {
        return $this->table->addDel();
    }
    
    public function setCreate($uri)
    {
        $p=new \P\AnchorCollection();
        $p[]=$this->header()->addButton()->icon("fa fa-plus")->addClass("btn-primary")->attr("href", $uri);
        return $p;
    }

    public function formCreate(){
        $this->table->attr("form-create",true);
        return $this;
    }
    
    public function subHTML($label, $callback, $index)
    {
        $url=$callback[0]->path()."/".$callback[1];
        return $this->table->add($label, function ($o) use ($url, $index) {
            if (is_object($o)) {
                if ($index) {
                    $url.="?".http_build_query([$index=> $o->$index]);
                } else {
                    $url.="?".http_build_query(["id"=> $o->ID()]);
                }
            } else {
                $url.="?".http_build_query([$index=> $o[$index]]);
            }
            
            return "<button class='btn btn-xs btn-primary table-childrow-btn table-childrow-close' data-url='$url' data-target=''><i class='fa fa-chevron-up'></i></button>";
        });
    }

}
