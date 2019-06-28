<?php

namespace Application;

use Application\Service\Http\RequestFactory;
use Application\Service\Http\RequestErrorResponseGenerator;
use League\Route\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\HttpHandlerRunner\Emitter\EmitterStack;
use Zend\HttpHandlerRunner\RequestHandlerRunner;
use Zend\ServiceManager\ServiceManager;

class Main implements RequestHandlerInterface
{
    private $serviceManager;

    public function __construct(array $applicationConfig)
    {
        $serviceManagerConfig = (isset($applicationConfig['service_manager']))
            ? $applicationConfig['service_manager']
            : [];

        $this->serviceManager = new ServiceManager($serviceManagerConfig);
        $this->serviceManager->setService('ApplicationConfig', $applicationConfig);
    }

    public static function init(array $applicationConfig = []): self
    {
        return new self($applicationConfig);
    }

    public function run(): void
    {
        $runner = new RequestHandlerRunner(
            $this,
            $this->serviceManager->get(EmitterStack::class),
            $this->serviceManager->get(RequestFactory::class),
            $this->serviceManager->get(RequestErrorResponseGenerator::class)
        );

        $runner->run();
    }

    /**
     * Handles a request and produces a response.
     *
     * @inheritdoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $router = $this->serviceManager->get(Router::class);

        return $router->dispatch($request);
    }
}
