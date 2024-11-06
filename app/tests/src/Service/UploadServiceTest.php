<?php

namespace App\Tests\src\Service;

use App\Exception\UploadFileInvalidTypeException;
use App\Service\UploadService;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

class UploadServiceTest extends AbstractTestCase
{
    private const string UPLOAD_DIRECTORY = '/tmp';

    private Filesystem $filesystem;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->filesystem = $this->createMock(Filesystem::class);
    }

    /**
     * @throws Exception
     */
    final public function testUploadBookFileInvalidExtension(): void
    {
        $this->expectException(UploadFileInvalidTypeException::class);

        $file = $this->createMock(UploadedFile::class);
        $file->expects($this->once())
            ->method('guessExtension')
            ->willReturn(null);

        $this->createService()->uploadBookFile(1, $file);
    }

    /**
     * @throws Exception
     */
    final public function testUploadBookFile(): void
    {
        $file = $this->createMock(UploadedFile::class);
        $file->expects($this->once())
            ->method('guessExtension')
            ->willReturn('jpg');

        $file->expects($this->once())
            ->method('move')
            ->with($this->equalTo('/tmp/book/1'), $this->callback(function (string $arg): bool {
                if (!str_ends_with($arg, '.jpg')) {
                    return false;
                }

                return Uuid::isValid(basename($arg, ',jpg'));
            }));

        $actualPath = pathinfo($this->createService()->uploadBookFile(1, $file));
        $this->assertEquals('/upload/book/1', $actualPath['dirname']);
        $this->assertEquals('jpg', $actualPath['extension']);
        $this->assertTrue(Uuid::isValid($actualPath['extension']));
    }

    final public function testDeleteBookFile(): void
    {
        $this->filesystem->expects($this->once())
            ->method('remove')
            ->with('/tmp/book/1/test.jpg');

        $this->createService()->deleteBookFile(1, 'test.jpg');
    }

    private function createService(): UploadService
    {
        return new UploadService($this->filesystem, self::UPLOAD_DIRECTORY);
    }
}
