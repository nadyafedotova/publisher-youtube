<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Subscriber;
use Doctrine\Persistence\ManagerRegistry;

class SubscriberRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscriber::class);
    }

    public function existsByEmail(string $email): bool
    {
        return null !== $this->findOneBy(['email' => $email]);
    }
}
