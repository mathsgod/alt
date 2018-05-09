<?php

class System_index extends ALT\Page
{
    public function get()
    {
        $panel = new BS\Panel("primary");
        $panel->heading("System");
        $panel->collapsible(false);

        $list = new BS\ListGroup();
        $list->addLinkedItem("Composer")->href("System/composer");
//    	$list->addLinkedItem("Composer (system)")->href("System/composer_system");
    	//$list->addLinkedItem("Bower")->href("System/bower");

        $list->addLinkedItem("Alter table charset")->href("System/alter_table_utf8");
        $list->addLinkedItem("alter table column charset to default")->href("System/alter_table_column_utf8");
        $list->addLinkedItem("Check TLS version")->href('System/tlscheck');
        // $list->addLinkedItem("Clear MyView Session")->href('System/session_clear');
        $list->addLinkedItem("Show DB Process")->href("System/db_process");
        $list->addLinkedItem("System info")->href("System/phpinfo");
//        $list->addLinkedItem("System check")->href("System/check");
        $list->addLinkedItem("Email test")->href("System/email_test");
        $list->addLinkedItem("Clear UI")->href("UI/clear")->classList->add('confirm');
        $list->addLinkedItem("System update")->href("System/update");
        $list->addLinkedItem("Shell")->href("System/shell");
        $list->addLinkedItem("CSV import")->href("System/csv_import");
        $list->addLinkedItem("Export")->href("System/export");
        $list->addLinkedItem("WebAPI log")->href("System/webapi");
        $list->addLinkedItem("PDO log")->href("System/pdo");
        $list->addLinkedItem("wikiParser")->href("System/wikiParser");
        $list->addLinkedItem("DB Check")->href("System/db_check");
        $list->addLinkedItem("DB migration")->href("System/db_migration");
        $list->addLinkedItem("Composer")->href("System/composer");
        $list->addLinkedItem("Adminer")->href("System/adminer");

        $list->addLinkedItem("Front translate")->href("System/front_translate_twig");
        $list->addLinkedItem("Unit test")->href("System/phpunit");

        $panel->body()->append($list);
        // $this->write($panel);
        $panel2 = new BS\Panel("primary");
        $panel2->heading("System locale");
        $panel2->collapsible(true);
        $panel2->body()->append(implode("<br/>", App\System::Locale()));

        $pg = new BS\PanelGroup();
        $pg->addPanel($panel);
        $pg->addPanel($panel2);
        $this->write($pg);
    }
}

?>