<?php

namespace Application\Service;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Interop\Container\ContainerInterface;

class ViewHelperInjector implements ViewHelperInjectorInterface
{
    /** @var array */
    private $helperNames;

    /** @var ContainerInterface */
    private $serviceManager;

    public function __construct(ContainerInterface $serviceManager, array $helperNames = [])
    {
        $this->helperNames    = $helperNames;
        $this->serviceManager = $serviceManager;
    }

    public function inject(Engine &$engine): void
    {
        foreach ($this->helperNames as $helperName) {
            $helper = $this->serviceManager->get($helperName);
            if (!($helper instanceof ExtensionInterface)) {
                continue;
            }

            /** @var $helper ExtensionInterface */
            $helper->register($engine);
        }
    }
}
