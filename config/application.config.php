<?php

use Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\HttpHandlerRunner\Emitter\EmitterStack as ZendEmitterStack;
use League\Plates\Engine as PlatesEngine;
use League\Route\Router as LeagueRouter;

return [
    'service_manager' => [
        'abstract_factories' => [
            ReflectionBasedAbstractFactory::class,
        ],

        'factories' => [
            /* Core */
            PlatesEngine::class
            => Application\Factory\EngineFactory::class,
            LeagueRouter::class
            => Application\Factory\RouterFactory::class,
            ZendEmitterStack::class
            => Application\Factory\EmitterStackFactory::class,
            'Config'
            => Application\Factory\ConfigFactory::class,
            Application\Middleware\ThrowableMiddleware::class
            => Application\Middleware\Factory\ThrowableMiddlewareFactory::class,
            Application\Strategy\ApplicationStrategy::class
            => Application\Strategy\Factory\ApplicationStrategyFactory::class,

            /* Controllers */
            Application\Controller\HomeController::class
            => ReflectionBasedAbstractFactory::class,
            Application\Controller\ImprintController::class
            => ReflectionBasedAbstractFactory::class,
            Application\Controller\PrivacyController::class
            => ReflectionBasedAbstractFactory::class,

            /* Services */
            Application\Service\ConfigMerger::class
            => InvokableFactory::class,
            Application\Service\Http\RequestFactory::class
            => InvokableFactory::class,
            Application\Service\Http\RequestErrorResponseGenerator::class
            => InvokableFactory::class,
            Application\Service\ViewHelperInjector::class
            => Application\Service\Factory\ViewHelperInjectorFactory::class,

            /* ViewHelpers */
            Application\ViewHelper\Url::class
            => Application\ViewHelper\Factory\UrlFactory::class,
        ],
    ],

    'view_path' => realpath(__DIR__) . '/../views',

    'view_namespaces' => [
        /* Configuration array for further view paths.
         * The default view_path remains as default view directory for
         * the template engine. Add more paths here, but remember to
         * namespace them. Example:
         *  'mail' => realpath(__DIR__) . '/../mail_views'
         * Usage in your controller: render('mail::foo');
         */
    ],

    'view_helpers' => [
        Application\ViewHelper\Url::class,
    ],

    'config_paths' => [
        realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php',
    ],
];
