<?php

namespace Application\Service;

use Traversable;
use Zend\Config\Config;
use Zend\Config\Factory as ConfigFactory;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Glob;

/**
 * ConfigMerger - Adopted from Zend Framework
 *
 * @see       https://github.com/zendframework/zend-modulemanager/blob/master/src/Listener/ConfigListener.php
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
class ConfigMerger
{
    const STATIC_PATH = 'static_path';
    const GLOB_PATH = 'glob_path';

    /**
     * @var array
     */
    protected $configs = [];

    /**
     * @var array
     */
    protected $mergedConfig = [];

    /**
     * @var ConfigMerger|null
     */
    protected $mergedConfigObject;

    /**
     * @var bool
     */
    protected $skipConfig = false;

    /**
     * @var array
     */
    protected $paths = [];

    /**
     * Merge all config files matched by the given glob()s
     *
     * This is only attached if config is not cached.
     *
     * @return ConfigMerger
     */
    public function mergeConfig()
    {
        // Load the config files
        foreach ($this->paths as $path) {
            $this->addConfigByPath($path['path'], $path['type']);
        }

        // Merge all of the collected configs
        $this->mergedConfig = [];
        foreach ($this->configs as $config) {
            $this->mergedConfig = ArrayUtils::merge($this->mergedConfig, $config);
        }

        return $this;
    }

    /**
     * getMergedConfig
     *
     * @param bool $returnConfigAsObject
     *
     * @return mixed
     */
    public function getMergedConfig($returnConfigAsObject = true)
    {
        if ($returnConfigAsObject === true) {
            if ($this->mergedConfigObject === null) {
                $this->mergedConfigObject = new Config($this->mergedConfig);
            }

            return $this->mergedConfigObject;
        }

        return $this->mergedConfig;
    }

    /**
     * setMergedConfig
     *
     * @param array $config
     *
     * @return ConfigMerger
     */
    public function setMergedConfig(array $config)
    {
        $this->mergedConfig       = $config;
        $this->mergedConfigObject = null;

        return $this;
    }

    /**
     * Add an array of glob paths of config files to merge after loading modules
     *
     * @param array|Traversable $globPaths
     *
     * @return ConfigMerger
     */
    public function addConfigGlobPaths($globPaths)
    {
        $this->addConfigPaths($globPaths, self::GLOB_PATH);

        return $this;
    }

    /**
     * Add a glob path of config files to merge after loading modules
     *
     * @param string $globPath
     *
     * @return ConfigMerger
     */
    public function addConfigGlobPath($globPath)
    {
        $this->addConfigPath($globPath, self::GLOB_PATH);

        return $this;
    }

    /**
     * Add an array of static paths of config files to merge after loading modules
     *
     * @param array|Traversable $staticPaths
     *
     * @return ConfigMerger
     */
    public function addConfigStaticPaths($staticPaths)
    {
        $this->addConfigPaths($staticPaths, self::STATIC_PATH);

        return $this;
    }

    /**
     * Add a static path of config files to merge after loading modules
     *
     * @param string $staticPath
     *
     * @return ConfigMerger
     */
    public function addConfigStaticPath($staticPath)
    {
        $this->addConfigPath($staticPath, self::STATIC_PATH);

        return $this;
    }

    /**
     * Add an array of paths of config files to merge after loading modules
     *
     * @param Traversable|array $paths
     * @param string            $type
     *
     * @throws \InvalidArgumentException
     */
    protected function addConfigPaths($paths, $type)
    {
        if ($paths instanceof Traversable) {
            $paths = ArrayUtils::iteratorToArray($paths);
        }

        if (!is_array($paths)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Argument passed to %s::%s() must be an array, '
                    . 'implement the Traversable interface, or be an '
                    . 'instance of Zend\Config\Config. %s given.',
                    __CLASS__,
                    __METHOD__,
                    gettype($paths)
                )
            );
        }

        foreach ($paths as $path) {
            $this->addConfigPath($path, $type);
        }
    }

    /**
     * Add a path of config files to load and merge after loading modules
     *
     * @param string $path
     * @param string $type
     *
     * @return ConfigMerger
     * @throws \InvalidArgumentException
     */
    protected function addConfigPath($path, $type)
    {
        if (!is_string($path)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Parameter to %s::%s() must be a string; %s given.',
                    __CLASS__,
                    __METHOD__,
                    gettype($path)
                )
            );
        }

        $this->paths[] = ['type' => $type, 'path' => $path];

        return $this;
    }

    /**
     * @param string            $key
     * @param array|Traversable $config
     *
     * @return ConfigMerger
     * @throws \InvalidArgumentException
     */
    protected function addConfig($key, $config)
    {
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }

        if (!is_array($config)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Config being merged must be an array, '
                    . 'implement the Traversable interface, or be an '
                    . 'instance of Zend\Config\Config. %s given.',
                    gettype($config)
                )
            );
        }

        $this->configs[$key] = $config;

        return $this;
    }

    /**
     * Given a path (glob or static), fetch the config and add it to the array
     * of configs to merge.
     *
     * @param string $path
     * @param string $type
     *
     * @return ConfigMerger
     */
    protected function addConfigByPath($path, $type)
    {
        switch ($type) {
            case self::STATIC_PATH:
                $this->addConfig($path, ConfigFactory::fromFile($path));
                break;

            case self::GLOB_PATH:
                // We want to keep track of where each value came from so we don't
                // use ConfigFactory::fromFiles() since it does merging internally.
                foreach (Glob::glob($path, Glob::GLOB_BRACE) as $file) {
                    $this->addConfig($file, ConfigFactory::fromFile($file));
                }
                break;
        }

        return $this;
    }
}
