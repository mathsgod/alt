<?

class System_example_date extends ALT\Page
{

    public function get()
    {

        $e = $this->createE([]);

        $e->add("Date")->date("date");

        $this->write($this->createForm($e));
    }
}
