<?php

declare(strict_types=1);

namespace App\Validation;

use Attribute;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

#[Attribute]
class AtLeastOneRequiredValidator extends ConstraintValidator
{
    private PropertyAccessorInterface $propertyAccessor;

    public function __construct(PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->propertyAccessor = $propertyAccessor ?? PropertyAccess::createPropertyAccessor();
    }

    public function validate(mixed $object, Constraint $constraint)
    {
        if (!$constraint instanceof AtLeastOneRequired) {
            throw new UnexpectedTypeException($constraint, AtLeastOneRequired::class);
        }

        $passed = array_filter($constraint->requiredField, function (string $required) use ($object) {
            return null !== $this->propertyAccessor->isReadable($object, $required);
        });

        if (!empty($passed)) {
            return;
        }

        $fieldList = implode(', ', $constraint->requiredField);

        foreach ($constraint->requiredField as $required) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ field }}', $fieldList)
                ->setCode(AtLeastOneRequired::ONE_REQUIRED_ERROR)
                ->atPath($required)
                ->addViolation();
        }
    }
}
