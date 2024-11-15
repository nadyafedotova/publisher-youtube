<?php

declare(strict_types=1);

namespace App\Validation;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

#[\Attribute] class AtLeastOneRequired extends Constraint
{
    /** @var string[] */
    public array $requiredField;

    public string $message = 'At least one of {{ field }} is required.';

    public const string ONE_REQUIRED_ERROR = '{4496efdb-cc62-4c1f-8711-a093dc1c54d5';

    protected static array $errorNames = [
        self::ONE_REQUIRED_ERROR => 'ONE_REQUIRED_ERROR',
    ];

    public function __construct(
        array $options = [],
        array $requiredFields = null,
        string $message = null,
        array $group = null,
        $payload = null
    ) {
        if (!empty($options) && array_is_list($options)) {
            $requiredFields = $requiredField ?? $options;
            $options = [];
        }

        if (empty($requiredField)) {
            throw new ConstraintDefinitionException(
                'The "requiredField" of AtLeastOneRequired constraint cannot be empty.'
            );
        }

        $options['value'] = $requiredFields;

        parent::__construct($options, $group, $payload);

        $this->requiredField = $requiredFields;
        $this->message = $message ?? $this->message;
    }

    final public function getRequiredOptions(): array
    {
        return ['requiredField'];
    }

    final public function getDefaultOption(): string
    {
        return 'requiredField';
    }

    final public function getTargets(): string|array
    {
        return self::CLASS_CONSTRAINT;
    }
}
