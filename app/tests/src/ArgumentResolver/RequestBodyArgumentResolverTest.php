<?php

namespace App\Tests\src\ArgumentResolver;

use App\ArgumentResolver\RequestBodyArgumentResolver;
use App\Attribute\RequestBody;
use App\Exception\RequestBodyConvertException;
use App\Exception\ValidationException;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\MockObject\Exception;
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

    final public function testNotSupports(): void
    {
        $meta = new ArgumentMetadata('some', null, false, false, null);

        $this->assertEmpty($this->resolver->resolve(new Request(), $meta));
    }

    final public function testResolveThrowsWhenDeserialize(): void
    {
        $this->expectException(RequestBodyConvertException::class);
        $request = new Request([], [], [], [], [], [], 'testing content');
        $meta = new ArgumentMetadata('some', \stdClass::class, false, false, null, false, [
            new RequestBody(),
        ]);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with('testing content', \stdClass::class, 'json')
            ->willThrowException(new \Exception());

        $this->resolver->resolve($request, $meta);
    }

    final public function testResolveThrowsWhenValidationFails(): void
    {
        $this->expectException(ValidationException::class);
        $body = ['test' => true];
        $encodedBody = json_encode($body);
        $request = new Request([], [], [], [], [], [], $encodedBody);
        $meta = new ArgumentMetadata('some', \stdClass::class, false, false, null, false, [
            new RequestBody(),
        ]);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($encodedBody, \stdClass::class, 'json')
            ->willReturn($body);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($body)
            ->willReturn(new ConstraintViolationList([
                new ConstraintViolation('error', null, [], null, 'some', null),
            ]));

        $this->resolver->resolve($request, $meta);
    }

    final public function testResolve(): void
    {
        $body = ['test' => true];
        $encodedBody = json_encode($body);
        $request = new Request([], [], [], [], [], [], $encodedBody);
        $meta = new ArgumentMetadata('some', \stdClass::class, false, false, null, false, [
            new RequestBody(),
        ]);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($encodedBody, \stdClass::class, 'json')
            ->willReturn($body);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($body)
            ->willReturn(new ConstraintViolationList([]));

        $actual = $this->resolver->resolve($request, $meta);

        $this->assertEquals($body, $actual[0]);
    }
}
