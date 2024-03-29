<?php
namespace ALT;

use R\Psr7\ObjectStream;

class GridPage extends Page
{
    protected $_grid;

    public function write($element)
    {
        $this->_grid->add($element, [0, 0]);
    }

    public function __invoke($request, $response)
    {
        $this->request = $request;
        $this->_grid = $this->createGrid([1]);
        $os = new ObjectStream(fopen("php://memory", "r+"));
        $os->write($this->_grid);
        $resp = $response->withBody($os);
        return parent::__invoke($request, $resp);
    }
}
