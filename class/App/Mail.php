<?php
namespace App;
class Mail extends \My\Mail {
    public function __construct($exceptions = false) {
        parent::__construct($exceptions);

        $smtp = \App::Config("user", "smtp");

        if ($smtp && $smtp->value) {
            $this->IsSMTP();
            $this->Host = (string)$smtp;
            $this->SMTPAuth = true;
            $this->Username = (string)\App::Config("user", "smtp-username");
            $this->Password = (string)\App::Config("user", "smtp-password");
        }
    }

    public function send() {
        foreach($this->to as $to) {
            $l = new MailLog();
            $l->subject = $this->Subject;

            $l->from = $this->From;
            $l->from_name = $this->FromName;
            $l->to = $to[0];
            $l->to_name = $to[1];
            $l->body = $this->Body;
            $l->altbody = $this->AltBody;

            $l->host = $this->Host;

            $l->save();
        }

        return parent::send();
    }
}

?>