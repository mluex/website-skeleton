<?php

namespace Application\ViewHelper;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class Url implements ExtensionInterface
{
    public function register(Engine $engine)
    {
        $engine->registerFunction('url', [$this, 'getUrl']);
    }

    public function getUrl(string $var): string
    {
        /*
         * Here you may add your own URL generation logic, e.g. forcing a certain scheme or
         * prepending the site's domain
         */
        return $var;
    }
}
