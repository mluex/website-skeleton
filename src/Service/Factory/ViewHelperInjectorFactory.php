<?php

namespace Application\Service\Factory;

use Application\Service\ViewHelperInjector;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class ViewHelperInjectorFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config          = $container->get('ApplicationConfig');
        $viewHelperNames = (isset($config['view_helpers'])) ? $config['view_helpers'] : [];

        return new ViewHelperInjector($container, $viewHelperNames);
    }
}
