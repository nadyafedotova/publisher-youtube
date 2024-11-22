<?php

declare(strict_types=1);

namespace App\Listener;

use App\Exception\ValidationException;
use App\Model\ErrorResponse;
use App\Model\ErrorValidationDetails;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

readonly class ValidationExceptionListener
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        if (!($throwable instanceof ValidationException)) {
            return;
        }

        $data = $this->serializer->serialize(
            new ErrorResponse($throwable->getMessage(), $this->formatViolations($throwable->getViolations())),
            'json',
        );

        $event->setResponse(new JsonResponse($data, Response::HTTP_BAD_REQUEST, [], true));
    }

    private function formatViolations(ConstraintViolationListInterface $violationList): ErrorValidationDetails
    {
        $details  = new ErrorValidationDetails();

        /** @var ConstraintViolationInterface $violation */
        foreach ($violationList as $violation) {
            $details->addViolations($violation->getPropertyPath(), $violation->getMessage());
        }

        return $details;
    }
}
