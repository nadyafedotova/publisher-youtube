<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;

class BookCategoryNotEmptyException extends RuntimeException
{
    public function __construct(int $booksCount)
    {
        parent::__construct(sprintf('Book categories has %d books', $booksCount));
    }
}
