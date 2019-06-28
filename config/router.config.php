<?php

use League\Route\Router;

/** @var Router $router */

$router->map('GET', '/', [Application\Controller\HomeController::class, 'indexAction']);
$router->map('GET', '/imprint', [Application\Controller\ImprintController::class, 'indexAction']);
$router->map('GET', '/privacy', [Application\Controller\PrivacyController::class, 'indexAction']);
