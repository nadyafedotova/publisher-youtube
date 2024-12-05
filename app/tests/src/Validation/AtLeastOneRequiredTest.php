<?php

declare(strict_types=1);

namespace App\Tests\src\Validation;

use App\Tests\AbstractTestCase;
use App\Validation\AtLeastOneRequired;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

class AtLeastOneRequiredTest extends AbstractTestCase
{
    final public function testEmptyOptionsException(): void
    {
        $this->expectException(ConstraintDefinitionException::class);

        new AtLeastOneRequired();
    }

    final public function testEmptyRequiredFieldsException(): void
    {
        $this->expectException(ConstraintDefinitionException::class);

        new AtLeastOneRequired([]);
    }

    final public function testShortOptionsToRequiredFields(): void
    {
        $constraint = new AtLeastOneRequired(['nextId', 'previousId']);
        $this->assertEquals(['nextId', 'previousId'], $constraint->requiredFields);
    }
}
