<?php

declare(strict_types=1);

namespace App\Service;

class PaginationUtils
{
    final public static function calcOOffset(int $page, int $pageLimit): int
    {
        return max($page - 1, 0) * $pageLimit;
    }

    final public static function calcPages(int $totalElements, int $pageLimit): int
    {
        return (int) ceil($totalElements / $pageLimit);
    }
}
