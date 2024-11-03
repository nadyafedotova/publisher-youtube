<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;

class BookCategoryAlreadyExistsException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Book category already exists');
    }
}
