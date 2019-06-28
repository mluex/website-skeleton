<?php

/**
 * Entry point file adopted from Zend Framework
 *
 * @see       https://github.com/zendframework/ZendSkeletonApplication/blob/master/public/index.php
 * @copyright Copyright (c) 2005-2016, Zend Technologies USA, Inc.
 * @license   https://github.com/zendframework/ZendSkeletonApplication/blob/master/LICENSE.md
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

use Application\Main;

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

// Composer autoloading
include __DIR__ . '/../vendor/autoload.php';

if (!class_exists(Main::class)) {
    throw new RuntimeException(
        "Unable to load application.\n"
    );
}

// Retrieve configuration
$appConfig = require __DIR__ . '/../config/application.config.php';

// Run the application
Main::init($appConfig)->run();
