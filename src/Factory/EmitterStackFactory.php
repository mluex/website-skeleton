<?php

namespace Application\Factory;

use Psr\Http\Message\ResponseInterface;
use Zend\HttpHandlerRunner\Emitter\EmitterInterface;
use Zend\HttpHandlerRunner\Emitter\EmitterStack;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use Zend\HttpHandlerRunner\Emitter\SapiStreamEmitter;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

/**
 * Adopted from Zend Framework
 *
 * @see       https://docs.zendframework.com/zend-httphandlerrunner/emitters/
 * @copyright Copyright (c) 2018, Zend Technologies USA, Inc. All rights reserved.
 * @license   https://github.com/zendframework/zend-httphandlerrunner/blob/master/LICENSE.md
 */
class EmitterStackFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $sapiStreamEmitter = new SapiStreamEmitter();

        $conditionalEmitter = new class ($sapiStreamEmitter) implements EmitterInterface
        {
            private $emitter;

            public function __construct(EmitterInterface $emitter)
            {
                $this->emitter = $emitter;
            }

            public function emit(ResponseInterface $response): bool
            {
                if (!$response->hasHeader('Content-Disposition')
                    && !$response->hasHeader('Content-Range')
                ) {
                    return false;
                }

                return $this->emitter->emit($response);
            }
        };

        $stack = new EmitterStack();
        $stack->push(new SapiEmitter());
        $stack->push($conditionalEmitter);

        return $stack;
    }
}
