<?php

declare(strict_types=1);

namespace App\Service\Recommendation\Exception;

use Throwable;

final class AccessDeniedException extends RecommendationException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('access denied', $previous);
    }
}
