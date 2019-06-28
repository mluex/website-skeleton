<?php

namespace Application\Service\Http;

use Psr\Http\Message\ResponseInterface;
use Throwable;
use Zend\Diactoros\Response;

class RequestErrorResponseGenerator
{
    public function __invoke(Throwable $exception): ResponseInterface
    {
        return new Response('php://memory', 500);
    }
}
