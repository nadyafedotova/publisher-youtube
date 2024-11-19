<?php

declare(strict_types=1);

namespace App\Validation;

use AllowDynamicProperties;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

#[AllowDynamicProperties]
#[\Attribute]
class AtLeastOneRequired extends Constraint
{
    /** @var string[] */
    public array $requiredFields;

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
            $requiredFields = $requiredFields ?? $options;
            $options = [];
        }

        if (empty($requiredFields)) {
            throw new ConstraintDefinitionException(
                'The "requiredFields" of AtLeastOneRequired constraint cannot be empty.'
            );
        }

        $options['value'] = $requiredFields;

        parent::__construct($options, $group, $payload);

        $this->requiredFields = $requiredFields;
        $this->message = $message ?? $this->message;
    }

    final public function getRequiredOptions(): array
    {
        return ['requiredFields'];
    }

    final public function getDefaultOption(): string
    {
        return 'requiredFields';
    }

    final public function getTargets(): string|array
    {
        return self::CLASS_CONSTRAINT;
    }
}
