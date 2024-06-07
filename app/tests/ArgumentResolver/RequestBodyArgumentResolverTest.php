<?php

namespace App\Tests\ArgumentResolver;

use App\ArgumentResolver\RequestBodyArgumentResolver;
use App\Attribute\RequestBody;
use App\Entity\Subscriber;
use App\Exception\RequestBodyConvertException;
use App\Exception\ValidationException;
use App\Model\SubscriberRequest;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\MockObject\Exception;
use stdClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestBodyArgumentResolverTest extends AbstractTestCase
{
    private SerializerInterface  $serializer;
    private ValidatorInterface $validator;
    private RequestBodyArgumentResolver $resolver;

    /**
     * @throws Exception
     */
    final public function setUp(): void
    {
        parent::setUp();

        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->resolver = new RequestBodyArgumentResolver($this->serializer, $this->validator);
    }

    final public function testResolveWithNonStringAttribute(): void
    {
        $request = new Request([], ['subscriberRequest' => new stdClass()]);
        $meta = new ArgumentMetadata('subscriberRequest', SubscriberRequest::class, false, false, null);

        $this->assertEmpty(iterator_to_array($this->resolver->resolve($request, $meta)));
    }

    final public function testResolveWithInvalidAttribute(): void
    {
        $meta = new ArgumentMetadata('subscriberRequest', stdClass::class, false, false, null);

        $this->assertEmpty(iterator_to_array($this->resolver->resolve(new Request(), $meta)));
    }

    final public function testResolveThrowsWhenDeserialize(): void
    {
        $this->expectException(RequestBodyConvertException::class);
        $request = new Request([], [], [], [], [], [], 'testing content');

        $meta = new ArgumentMetadata('subscriberRequest', SubscriberRequest::class, false, false, null, false, [
            new RequestBody(),
        ]);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with('testing content', SubscriberRequest::class, 'json')
            ->willThrowException(new \Exception());

        $this->resolver->checkRequestBodyConvertException($request, $meta);
    }

    final public function testResolveThrowsWhenValidationFails(): void
    {
        $this->expectException(ValidationException::class);
        $body = ['test' => true];
        $encodedBody = json_encode($body);
        $request = new Request([], [], [], [], [], [], $encodedBody);

        $meta = new ArgumentMetadata('subscriberRequest', SubscriberRequest::class, false, false, null, false, [
            new RequestBody(),
        ]);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($encodedBody, SubscriberRequest::class, 'json')
            ->willReturn($body);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($body)
            ->willReturn(new ConstraintViolationList([new ConstraintViolation('Invalid email', null, [], null, 'email', null),
                ]));

        $this->resolver->checkRequestBodyConvertException($request, $meta);
    }

    final public function testResolve(): void
    {
        $body = ['test' => true];
        $encodedBody = json_encode($body);
        $request = new Request([], [], [], [], [], [], $encodedBody);

        $meta = new ArgumentMetadata('subscriberRequest', SubscriberRequest::class, false, false, null, false, [
            new RequestBody(),
        ]);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($encodedBody, SubscriberRequest::class, 'json')
            ->willReturn($body);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($body)
            ->willReturn(new ConstraintViolationList([]));

        $actual = $this->resolver->checkRequestBodyConvertException($request, $meta);

        $this->assertEquals($body, $actual);
    }
}
