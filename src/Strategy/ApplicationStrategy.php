<?php

namespace Application\Strategy;

use Application\Middleware\ThrowableMiddleware;
use League\Route\Strategy\ApplicationStrategy as LeagueApplicationStrategy;
use Psr\Http\Server\MiddlewareInterface;
use Throwable;

/**
 * Extension of League's ApplicationStrategy to enable DI / overriding of middleware service
 */
class ApplicationStrategy extends LeagueApplicationStrategy
{
    /** @var ThrowableMiddleware $throwableMiddleware */
    protected $throwableMiddleware;

    public function __construct(ThrowableMiddleware $throwableMiddleware)
    {
        $this->throwableMiddleware = $throwableMiddleware;
    }

    /**
     * Return a middleware that simply throws an error
     *
     * @param Throwable $error
     *
     * @return MiddlewareInterface
     */
    protected function throwThrowableMiddleware(Throwable $error): MiddlewareInterface
    {
        $this->throwableMiddleware->setError($error);

        return $this->throwableMiddleware;
    }
}
