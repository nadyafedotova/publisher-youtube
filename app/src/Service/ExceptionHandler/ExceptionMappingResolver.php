<?php

declare(strict_types=1);

namespace App\Service\ExceptionHandler;

use InvalidArgumentException;
use Throwable;

class ExceptionMappingResolver
{
    /**
     * @var ExceptionMapping[]
     */
    private array $mapping = [];

    public function __construct(array $mappings)
    {
        foreach ($mappings as $class => $mapping) {
            if (empty($mapping['code'])) {
                throw new InvalidArgumentException('"code" is mandatory for class'.$class);
            }
            $this->addMapping(
                $class,
                $mapping['code'],
                $mapping['hidden'] ?? true,
                $mapping['loggable'] ?? false
            );
        }
    }

    public function resolve(string $throwableClass): ?ExceptionMapping
    {
        $foundMapping = null;

        foreach ($this->mapping as $class => $mapping) {
            if ($throwableClass === $class || is_subclass_of($throwableClass, $class)) {
                $foundMapping = $mapping;
                break;
            }
        }

        return $foundMapping;
    }

    private function addMapping(string $class, int $code, bool $hidden, bool $loggable): void
    {
        $this->mapping[$class] = new ExceptionMapping($code, $hidden, $loggable);
    }
}
