<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Subscriber;
use App\Exception\SubscriberAlreadyExistsException;
use App\Model\SubscriberRequest;
use App\Repository\SubscriberRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class SubscriberService
{
    public function __construct(
        private SubscriberRepository $subscriberRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    final public function subscribe(SubscriberRequest $subscriberRequest): void
    {
        if ($this->subscriberRepository->existsByEmail($subscriberRequest->getEmail())) {
            throw new SubscriberAlreadyExistsException();
        }

        $subscriber = new Subscriber();
        $subscriber->setEmail($subscriberRequest->getEmail());

        $this->entityManager->persist($subscriber);
        $this->entityManager->flush();
    }
}
