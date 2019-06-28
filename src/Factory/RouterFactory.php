<?php

namespace Application\Factory;

use Application\Strategy\ApplicationStrategy;
use League\Route;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class RouterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $strategy = $container->get(ApplicationStrategy::class);
        $strategy->setContainer($container);

        $router = new Route\Router();
        $router->setStrategy($strategy);

        include_once realpath(__DIR__) . '/../../config/router.config.php';

        return $router;
    }
}
