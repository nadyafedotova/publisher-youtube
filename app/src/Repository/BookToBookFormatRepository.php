<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\BookToBookFormat;
use Doctrine\Persistence\ManagerRegistry;

class BookToBookFormatRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookToBookFormat::class);
    }
}
