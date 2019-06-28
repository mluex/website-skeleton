<?php

namespace Application\Service\Http;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequestFactory;

class RequestFactory
{
    public function __invoke(): ServerRequestInterface
    {
        return ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
    }
}
