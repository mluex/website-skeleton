<?php

namespace Application\Factory;

use Application\Service\ViewHelperInjector;
use Application\Service\ViewHelperInjectorInterface;
use League\Plates\Engine;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class EngineFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config   = $container->get('ApplicationConfig');
        $viewPath = (isset($config['view_path'])) ? $config['view_path'] : null;

        // Instantiate Engine with default view path
        $engine = new Engine($viewPath);

        $namespaces = (isset($config['view_namespaces']) && is_array($config['view_namespaces']))
            ? $config['view_namespaces']
            : [];

        // Register additional folders mapped to namespaces
        foreach ($namespaces as $namespace => $path) {
            $engine->addFolder($namespace, $path);
        }

        // Inject view helpers
        $injector = $container->get(ViewHelperInjector::class);
        if ($injector instanceof ViewHelperInjectorInterface) {
            $injector->inject($engine);
        }

        return $engine;
    }
}
