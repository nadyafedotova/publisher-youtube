<?php

declare(strict_types=1);

namespace App\Tests\src\Service;

use App\Service\SortContext;
use App\Service\SortPosition;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class SortContextTest extends AbstractTestCase
{
    public static function neighboursProvider(): array
    {
        return [
            [null, 2, SortPosition::AsLast, 2],
            [3, null, SortPosition::AsFirst, 3],
            [6, 5, SortPosition::Between, 6],
        ];
    }

    #[DataProvider('neighboursProvider')]
    final public function testFromNeighbours(?int $newtId, ?int $previousId, SortPosition $sortPosition, int $expectedNearId): void
    {
        $sortContext = SortContext::fromNeighbours($newtId, $previousId);

        $this->assertEquals($sortPosition, $sortContext->getPosition());
        $this->assertEquals($expectedNearId, $sortContext->getNearId());
    }
}
