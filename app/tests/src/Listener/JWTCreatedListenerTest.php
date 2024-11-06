<?php

namespace App\Tests\src\Listener;

use App\Listener\JWTCreatedListener;
use App\Tests\AbstractTestCase;
use App\Tests\MockUtils;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListenerTest extends AbstractTestCase
{
    /**
     * @throws \ReflectionException
     */
    final public function testInvoke(): void
    {
        $user = MockUtils::createUser();
        MockUtils::setEntityId($user, 123);

        $listener = new JWTCreatedListener();
        $event = new JWTCreatedEvent(['flag' => true], $user, []);

        $listener($event);

        self::assertEquals(['flag' => true, 'id' => 123], $event->getData());
    }
}
