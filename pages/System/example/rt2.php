<?

class System_example_rt2 extends ALT\Page
{
    public function get()
    {
        $rt = $this->createRT2([$this, "ds"]);
        $rt->add("Username", "username")->searchMultiple(App\User::Query(), "username", "user_id");
        $this->write($rt);
    }

    public function ds($rt)
    {
        $rt->source = App\User::Query();
        return $rt;
    }
}
