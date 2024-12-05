<?php

declare(strict_types=1);

namespace App\Tests\src\Validation;

use App\Validation\AtLeastOneRequired;
use App\Validation\AtLeastOneRequiredValidator;
use stdClass;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class AtLeastOneRequiredValidatorTest extends ConstraintValidatorTestCase
{
    private PropertyAccessorInterface $propertyAccessor;

    final protected function setUp(): void
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();

        parent::setUp();
    }

    final public function testValidateExceptionOnUnexpectedType(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate([], new NotNull());
    }

    final public function testValidateNoRequired(): void
    {
        $constraint = new AtLeastOneRequired(['nextId']);
        $object = new stdClass();
        $object->nextId = null;

        $this->validator->validate($object, $constraint);

        $this->buildViolation('At least one of {{ field }} is required.')
            ->setParameter('{{ field }}', 'nextId')
            ->atPath('property.path.nextId')
            ->setCode(AtLeastOneRequired::ONE_REQUIRED_ERROR)
            ->assertRaised();
    }

    final public function testValidate(): void
    {
        $constraint = new AtLeastOneRequired(['nextId']);
        $object = new stdClass();
        $object->nextId = 'test';

        $this->validator->validate($object, $constraint);
        $this->assertNoViolation();
    }

    final protected function createValidator(): ConstraintValidatorInterface
    {
        return new AtLeastOneRequiredValidator($this->propertyAccessor);
    }
}
