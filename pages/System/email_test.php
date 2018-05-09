<?php
// Created By: Raymond Chong
// Created Date: 30/05/2012
// Last Updated:
class System_email_test extends ALT\Page
{
    public function post()
    {
        $mail = new App\Mail();
        $mail->isSMTP();
        $mail->Subject = $_POST["subject"];
        $mail->setFrom($_POST["sender"]);
        $mail->addAddress($_POST["receiver"]);

        if ($_POST["cc"]) $mail->addCC($_POST["cc"]);
        if ($_POST["bcc"]) $mail->addBCC($_POST["bcc"]);
        $mail->msgHTML($_POST["content"]);

        if ($_POST["smtp"]) {
            $mail->Host = $_POST["smtp"];
            $mail->Port = 25;
            $mail->SMTPAuth = true;
            $mail->Username = $_POST["smtp-username"];
            $mail->Password = $_POST["smtp-password"];
        }

        if ($mail->send()) {
            $this->app->alert->success("mail sent");
        } else {
            $this->app->alert->danger($mail->ErrorInfo);
        }
        $this->_redirect();
    }

    public function get()
    {
        $mv = $this->createE();
        $mv->add("smtp")->input("smtp")->val(App\Config::_("user", "smtp"));
        $mv->add("smtp-username")->input("smtp-username")->val(App\Config::_("user", "smtp-username"));
        $mv->add("smtp-password")->input("smtp-password")->val(App\Config::_("user", "smtp-password"));
        $mv->add("return-path")->input("return-path")->val(App\Config::_("user", "return-path"));

        $mv->addHr();
        $mv->add("Sender")->input("sender")->val("raymond@hostlink.com.hk");
        $mv->add("Receiver")->input("receiver")->Val("raymond@hostlink.com.hk");
        $mv->add("CC")->input("cc")->val("raymond@hostlink.com.hk");
        $mv->add("BCC")->input("bcc")->val("raymond@hostlink.com.hk");
        $mv->add("Subject")->input("subject")->val("subject");
        $mv->add("Content")->textarea("content")->html("This is a test mail");

        $this->write($this->createForm($mv));
    }
}