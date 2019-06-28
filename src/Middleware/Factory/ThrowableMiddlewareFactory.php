<?php

namespace Application\Middleware\Factory;

use Application\Middleware\ThrowableMiddleware;
use League\Plates\Engine;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class ThrowableMiddlewareFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ThrowableMiddleware(
            $container->get(Engine::class),
            ($container->get('Config')['error_pages'] ?? [])
        );
    }
}
