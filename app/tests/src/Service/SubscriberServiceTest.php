<?php

namespace App\Tests\src\Service;

use App\Entity\Subscriber;
use App\Exception\SubscriberAlreadyExistsException;
use App\Model\SubscriberRequest;
use App\Repository\SubscriberRepository;
use App\Service\SubscriberService;
use App\Tests\AbstractTestCase;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Exception;

class SubscriberServiceTest extends AbstractTestCase
{
    private SubscriberRepository $subscriberRepository;

    private EntityManagerInterface $manager;

    private const string EMAIL = 'test@test.com';

    /**
     * @throws Exception
     */
    final protected function setUp(): void
    {
        parent::setUp();
        $this->subscriberRepository = $this->createMock(SubscriberRepository::class);
        $this->manager = $this->createMock(EntityManagerInterface::class);
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

        (new SubscriberService($this->subscriberRepository, $this->manager))->subscribe($request);
    }

    final public function testSubscribe(): void
    {
        $this->subscriberRepository->expects($this->once())
            ->method('existsByEmail')
            ->with(self::EMAIL)
            ->willReturn(false);

        $expectedSubscriber = new Subscriber();
        $expectedSubscriber->setEmail(self::EMAIL);
        $this->manager->expects($this->once())
            ->method('persist')
            ->with($expectedSubscriber);

        $this->manager->expects($this->once())
            ->method('flush');

        $request = new SubscriberRequest();
        $request->setEmail(self::EMAIL);

        (new SubscriberService($this->subscriberRepository, $this->manager))->subscribe($request);
    }
}
