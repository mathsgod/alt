<?

class System_test extends ALT\Page
{
    public function get()
    {
        $e = $this->createE([]);
        $e->add("test")->roxyfileman("image1");

        $this->write($this->createForm($e));
    }
}
