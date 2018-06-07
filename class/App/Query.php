<?
namespace App;

use R\RSList;

class Query extends \R\DB\Query
{

    public $class;
    private $_list;
    public function __construct($class)
    {
        $this->class = $class;
        parent::__construct($class::__db());
    }

    public function getIterator()
    {

        $iterator = parent::getIterator();

        return  new RSList($iterator, $this->class);
        return $this->_list;
    }
}