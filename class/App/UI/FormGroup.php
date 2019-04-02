<?
namespace App\UI;

class FormGroup extends \P\Element
{
    public function __construct()
    {
        parent::__construct("div");
//        $this->attributes["is"]="bs-form-group";
        $this->classList[] = "form-group";
    }

}