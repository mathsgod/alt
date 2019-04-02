<?
namespace App\UI;

use P\Document;

class Element  extends \P\Element
{
    public function setAttribute($name, $value)
    {
        return parent::setAttribute($name, is_array($value) ? json_encode($value) : $value);
    }
}
