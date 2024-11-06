<?php

namespace App\Tests\src\Service;

use App\Entity\Subscriber;
use App\Exception\SubscriberAlreadyExistsException;
use App\Model\SubscriberRequest;
use App\Repository\SubscriberRepository;
use App\Service\SubscriberService;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\MockObject\Exception;

class SubscriberServiceTest extends AbstractTestCase
{
    private SubscriberRepository $subscriberRepository;


    private const string EMAIL = 'test@test.com';

    /**
     * @throws Exception
     */
    final protected function setUp(): void
    {
        parent::setUp();
        $this->subscriberRepository = $this->createMock(SubscriberRepository::class);
    }

    final public function testSubscribeAlreadyExists(): void
    {
        $this->expectException(SubscriberAlreadyExistsException::class);

        $this->subscriberRepository->expects($this->once())
            ->method('existsByEmail')
            ->with(self::EMAIL)
            ->willReturn(true);

        $request = new SubscriberRequest();
        $request->setEmail(self::EMAIL);

        $this->subscriberRepository->expects($this->once())
            ->method('saveAndCommit')
            ->with($request);

        (new SubscriberService($this->subscriberRepository))->subscribe($request);
    }

    final public function testSubscribe(): void
    {
        $this->subscriberRepository->expects($this->once())
            ->method('existsByEmail')
            ->with(self::EMAIL)
            ->willReturn(false);

        $expectedSubscriber = new Subscriber();
        $expectedSubscriber->setEmail(self::EMAIL);

        $request = new SubscriberRequest();
        $request->setEmail(self::EMAIL);
        $this->subscriberRepository->expects($this->once())
            ->method('saveAndCommit')
            ->with($request);


        (new SubscriberService($this->subscriberRepository))->subscribe($request);
    }
}
