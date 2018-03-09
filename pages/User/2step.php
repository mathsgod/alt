<?php
// Created By: Raymond Chong
// Created Date: 2013-04-10
// Last Updated:
class User_2step extends ALT\Page {


    public function barcode($data) {
        $this->write(file_get_contents("https://chart.apis.google.com/chart?cht=qr&chs=300x300&chl=".$data));

        return;

        // include 2D barcode class (search for installation path)
        $p = App\Plugin::load("tcpdf");
        require_once($p->path . "/tcpdf_barcodes_2d.php");
        // set the barcode content and type
        $barcodeobj = new TCPDF2DBarcode($data, 'QRCODE,H');
        // output the barcode as PNG image
        $barcodeobj->getBarcodePNG(6, 6, array(0, 0, 0));


    }

    public function post($cmd) {
        switch ($cmd) {
            case "remove":
                $u = App::User();
                $u->secret = "";
                $u->save();
                App::Msg("2-step verification removed");
                App::redirect();
                break;
            default:
                App::redirect("User/2step?auto_create=1");
        }
    }

    private function create_code() {
        require_once(SYSTEM . "/plugins/GoogleAuthenticator/GoogleAuthenticator.php");
        $g = new GoogleAuthenticator();
        $secret = $g->generateSecret();
        $u = App::User();
        $u->secret = $secret;
        $u->save();

        $b = $this->createBox();
        $b->header("2-step secret key");
        $b->body()->append("Your secret key are created: <b>$secret</b>");

        $user = App::User()->username;
        $hostname = App::Config("user", "domain");
        $data = urlencode(sprintf("otpauth://totp/%s@%s?secret=%s", $user, $hostname, $secret));
        $b->body()->append("<div align='center'><img src='User/2step/barcode?data={$data}' /></div>");

        $this->write($b);
    }

    public function get($auto_create) {
        // $this->header()->setTitle("2-step verfication");
        if ($auto_create) {
            $this->create_code();
            return ;
        }

        if (App::User()->secret) {
            $f = $this->createForm("2-step verification already set, remove it?");
            $f->action("User/2step?cmd=remove");
            $this->write($f);
        } else {
            $f = $this->createForm("Click submit to create 2-step verification");
            $f->action("");
            $this->write($f);
        }
    }
}

?>
