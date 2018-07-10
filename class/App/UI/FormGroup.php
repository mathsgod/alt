<?
namespace App\UI;

class FormGroup extends \P\HTMLDivElement
{
    public function __construct()
    {
        parent::__construct();
//        $this->attributes["is"]="bs-form-group";
        $this->classList[] = "form-group";
    }

}