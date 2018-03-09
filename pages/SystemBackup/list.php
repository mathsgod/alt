<?php

// Created By: Raymond Chong
// Last Updated:
use App\SystemBackup;

class SystemBackup_list extends App\Page
{
    public function get()
    {
        $myt = $this->createRT([$this, "ds"]);
        $myt->addDel();
        $myt->add("File name", "filename");
        $myt->add("Size", "size");
        $myt->add("Restore")->button()->addClass('btn-danger confirm')->text("restore")->href(function ($o) {
            return $o->uri('restore');
        });

        $this->write($myt);
    }

    public function ds($jv)
    {
        $files = array();
        foreach (glob(getcwd() . '/backup/*') as $file) {
            
            $fi = new SplFileInfo($file);

            $f = new SystemBackup();
            $f->filename = $fi->getFilename();
            $f->size = $fi->getSize() . " bytes";
            $files[] = $f;
        }

        return array("total" => sizeof($files), "data" => $files);
    }
}

?>