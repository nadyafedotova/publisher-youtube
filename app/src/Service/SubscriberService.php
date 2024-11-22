<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Subscriber;
use App\Exception\SubscriberAlreadyExistsException;
use App\Model\SubscriberRequest;
use App\Repository\SubscriberRepository;

readonly class SubscriberService
{
    public function __construct(
        private SubscriberRepository $subscriberRepository
    ) {
    }

    final public function subscribe(SubscriberRequest $subscriberRequest): void
    {
        if ($this->subscriberRepository->existsByEmail($subscriberRequest->getEmail())) {
            throw new SubscriberAlreadyExistsException();
        }

        $this->subscriberRepository->saveAndCommit((new Subscriber())->setEmail($subscriberRequest->getEmail()));
    }
}
