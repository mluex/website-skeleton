<?php

namespace Application\Service;

use League\Plates\Engine;

interface ViewHelperInjectorInterface
{
    public function inject(Engine &$engine): void;
}
