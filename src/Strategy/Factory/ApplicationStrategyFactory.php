<?php

namespace Application\Strategy\Factory;

use Application\Middleware\ThrowableMiddleware;
use Application\Strategy\ApplicationStrategy;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class ApplicationStrategyFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ApplicationStrategy(
            $container->get(ThrowableMiddleware::class)
        );
    }
}
