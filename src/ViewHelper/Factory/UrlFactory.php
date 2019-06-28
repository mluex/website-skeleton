<?php

namespace Application\ViewHelper\Factory;

use Application\ViewHelper\Url;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class UrlFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Url();
    }
}
