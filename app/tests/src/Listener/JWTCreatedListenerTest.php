<?php

namespace App\Tests\src\Listener;

use App\Listener\JWTCreatedListener;
use App\Tests\AbstractTestCase;
use App\Tests\MockUtils;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Random\RandomException;
use ReflectionException;

class JWTCreatedListenerTest extends AbstractTestCase
{
    /** @throws ReflectionException|RandomException */
    final public function testInvoke(): void
    {
        $user = MockUtils::createUser();
        MockUtils::setEntityId($user, 123);

        $listener = new JWTCreatedListener();
        $event = new JWTCreatedEvent(['flag' => true], $user, []);

        $listener($event);

        $this->assertEquals(['flag' => true, 'id' => 123], $event->getData());
    }
}
