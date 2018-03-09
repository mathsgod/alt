<?php

class My {
    public static function User() {
        return $_SESSION["app"]["user"];
    }

    public static function T($ds) {
        $t = new My\T($ds);
        return $t;
    }

    public static function E($object) {
        return new My\E($object);
    }

    public static function V($object) {
        return new My\V($object);
    }

    public static function Box($body, $title = null) {
        $box = new ALT\Box();
        if ($title)$box->header($title);
        $box->body()->append((string)$body);
        return $box;
    }

    public static function Form($compontent = null, $multipart = false) {
        $form = new ALT\Form();
        if ($multipart) $form->attr("enctype", "multipart/form-data");
        $form->attr("method", "post");

        $form->box()->body()->append((string)$compontent);
        return $form;
    }

    public static function Language() {
        return $_SESSION["app"]["user"]->language;
    }
}

?>