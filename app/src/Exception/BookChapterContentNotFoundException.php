<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;

class BookChapterContentNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Book content not found');
    }
}
