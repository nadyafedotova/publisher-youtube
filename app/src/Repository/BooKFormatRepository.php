<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\BookFormat;
use App\Exception\BookFormatNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BooKFormatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookFormat::class);
    }

    public function getById(int $id): BookFormat
    {
        $format = $this->find($id);
        if (null === $format) {
            throw new BookFormatNotFoundException();
        }

        return $format;
    }
}
