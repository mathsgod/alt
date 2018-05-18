<?php
namespace App;

class Mail extends \My\Mail
{
    public function __construct($exceptions = false)
    {
        parent::__construct($exceptions);

        $config = \App::_()->config["user"];
        $smtp = $config["smtp"];

        if ($smtp && $smtp->value) {
            $this->IsSMTP();
            $this->Host = (string)$smtp;
            $this->SMTPAuth = true;
            $this->Username = $config["smtp-username"];
            $this->Password = $config["smtp-password"];
        }
    }

    public function send()
    {
        foreach ($this->to as $to) {
            $l = new MailLog();
            $l->subject = $this->Subject;

            $l->from = $this->From;
            $l->from_name = $this->FromName;
            $l->to = $to[0];
            $l->to_name = $to[1];
            $l->body = $this->Body;
            $l->altbody = $this->AltBody;

            $l->host = $this->Host;

            $l->save(false);
        }

        return parent::send();
    }
}