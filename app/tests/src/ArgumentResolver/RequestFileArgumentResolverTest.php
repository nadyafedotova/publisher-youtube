<?php

namespace App\Tests\src\ArgumentResolver;

use App\ArgumentResolver\RequestFileArgumentResolver;
use App\Attribute\RequestFile;
use App\Exception\ValidationException;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\MockObject\Exception;
use stdClass;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestFileArgumentResolverTest extends AbstractTestCase
{
    private ValidatorInterface $validator;

    /**
     * @throws Exception
     */
    final public function setUp(): void
    {
        parent::setUp();

        $this->validator = $this->createMock(ValidatorInterface::class);
    }

    final public function testSupports(): void
    {
        $meta = new ArgumentMetadata('some', null, false, false, null, false);

        $this->assertEmpty($this->createResolver()->resolve(new Request(), $meta));
    }

    final public function testNotSupports(): void
    {
        $meta = new ArgumentMetadata('some', null, false, false, null);

        $this->assertEmpty(iterator_to_array($this->createResolver()->resolve(new Request(), $meta)));
    }

    final public function testResolveThrowsWhenValidationFails(): void
    {
        $this->expectException(ValidationException::class);

        $file = new UploadedFile('path', 'field', null, UPLOAD_ERR_NO_FILE, true);
        $request = new Request();
        $request->files->add(['file' => $file]);

        $meta = new ArgumentMetadata('some', stdClass::class, false, false, null, false, [
            new RequestFile('file', []),
        ]);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($file, [])
            ->willReturn(new ConstraintViolationList([
                new ConstraintViolation('error', null, [], null, 'some', null),
            ]));

        $this->createResolver()->resolve($request, $meta);
    }

    final public function testResolveThrowsWhenConstraintFails(): void
    {
        $this->expectException(ValidationException::class);

        $constraints = [new NotNull()];
        $file = new UploadedFile('path', 'field', null, UPLOAD_ERR_NO_FILE, true);
        $request = new Request();
        $request->files->add(['file' => $file]);

        $meta = new ArgumentMetadata('some', stdClass::class, false, false, null, false, [
            new RequestFile('file', $constraints),
        ]);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($file, $constraints)
            ->willReturn(new ConstraintViolationList([
                new ConstraintViolation('error', null, [], null, 'some', null),
            ]));

        $this->createResolver()->resolve($request, $meta);
    }

    final public function testResolve(): void
    {
        $file = new UploadedFile('path', 'field', null, UPLOAD_ERR_NO_FILE, true);
        $request = new Request();
        $request->files->add(['file' => $file]);

        $meta = new ArgumentMetadata('some', stdClass::class, false, false, null, false, [
            new RequestFile('file', []),
        ]);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($file, [])
            ->willReturn(new ConstraintViolationList([]));

        $actual = $this->createResolver()->resolve($request, $meta);

        $this->assertEquals($file, iterator_to_array($actual)[0]);
    }

    private function createResolver(): RequestFileArgumentResolver
    {
        return new RequestFileArgumentResolver($this->validator);
    }
}
