<?php

namespace Application\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

class PrivacyController extends PlatesEngineAwareController
{
    public function indexAction(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write($this->engine->render('privacy/index'));

        return $response;
    }
}
