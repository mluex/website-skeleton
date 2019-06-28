<?php

namespace Application\Controller;

use League\Plates\Engine;

abstract class PlatesEngineAwareController
{
    protected $engine;

    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }
}
