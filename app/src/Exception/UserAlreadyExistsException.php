<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;

class UserAlreadyExistsException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('user already exists');
    }
}
