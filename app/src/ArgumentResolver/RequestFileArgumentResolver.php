<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

use App\Attribute\RequestFile;
use App\Exception\RequestBodyConvertException;
use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

readonly class RequestFileArgumentResolver implements ValueResolverInterface
{
    public function __construct(
        private ValidatorInterface $validator,
    ) {
    }

    final public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $attributes = $argument->getAttributes(RequestFile::class, ArgumentMetadata::IS_INSTANCEOF);
        if (!$attributes) {
            return [];
        }

        /** @var RequestFile $attribute */
        $attribute = $attributes[0];

        $uploadedFile = $request->files->get($attribute->getField());

        $errors = $this->validator->validate($uploadedFile, $uploadedFile->getConstraints());
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        yield $uploadedFile;
    }
}
