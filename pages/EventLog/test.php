<?

class EventLog_test extends ALT\Page
{
    public function get()
    {
        $tab = $this->createTab();
        $tab->add("All event log", "list2");
        $this->write($tab);
    }
}
