<?php

namespace Application\Factory;

use Application\Service\ConfigMerger;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class ConfigFactory implements FactoryInterface
{
    /**
     * Generates merged config array from autoloaded config files
     *
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return array
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config      = $container->get('ApplicationConfig');
        $configPaths = (isset($config['config_paths'])) ? $config['config_paths'] : [];

        /** @var ConfigMerger $merger */
        $merger = $container->get(ConfigMerger::class);
        $merger->addConfigGlobPaths($configPaths);
        $merger->mergeConfig();

        return $merger->getMergedConfig(false);
    }
}
