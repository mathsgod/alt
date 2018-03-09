<?php
class User_reset_password extends ALT\Page
{
    public function post()
    {
        if (App::User()->isAdmin()) {
            $u = $this->object();
        } else {
            $u = App::User();
        }
        $u->password = $_POST["password"];
        $u->save();
        App::Msg("Password updated");
        $this->_redirect();
    }

    public function get()
    {
        $obj = $this->object();

        $obj->password = "";
        $mv = $this->createE($obj);
        $mv->add("New password")->input("password")->type("password")->required()->attr("id", "password")->minLength(App::Config(
            "user",
            "password-length"
        ));
        $mv->add("Retype password")->input("password2")->type("password")->required();
        $f = $this->createForm($mv);
        $f->action();
        $this->write($f);
    }
}

?>
<script>
$(function(){
	$("[name='password2']").rules("add",{
		equalTo: "#password"
	});
});
</script>