<?php

namespace Application\Middleware;

use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;
use League\Route\Http\Exception\{MethodNotAllowedException, NotFoundException};
use Zend\Diactoros\Response;

/**
 * Displays a rendered error page if specific exception have been thrown while processing the request.
 * You may add more rules below.
 *
 * Unhandled exception still lead to error 500
 */
class ThrowableMiddleware implements MiddlewareInterface
{
    /** @var Throwable $error */
    private $error;

    /** @var Engine $engine */
    private $engine;

    /** @var array $errorPagesConfig */
    private $errorPagesConfig;

    public function __construct(Engine $engine, array $errorPagesConfig)
    {
        $this->engine = $engine;
        $this->errorPagesConfig = $errorPagesConfig;
    }

    public function setError(Throwable $error): void
    {
        $this->error = $error;
    }

    private function getViewPath(string $name): string
    {
        return $this->errorPagesConfig[$name] ?? 'error/' . $name;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->error instanceof NotFoundException) {
            $response = new Response();
            $response = $response->withStatus(404);
            $response->getBody()->write($this->engine->render($this->getViewPath('not_found')));

            return $response;
        } elseif ($this->error instanceof MethodNotAllowedException) {
            $response = new Response();
            $response = $response->withStatus(405);
            $response->getBody()->write($this->engine->render($this->getViewPath('method_not_allowed')));

            return $response;
        }

        // Introduce more error pages here if you like

        throw $this->error;
    }
}
