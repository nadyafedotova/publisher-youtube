<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\UploadFileInvalidTypeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

readonly class UploadService
{
    private const string LINK_BOOK_PATTERN = '/upload/book/%d/%s';

    public function __construct(
        private Filesystem $filesystem,
        private string     $uploadDir,
    ) {
    }

    public function deleteBookFile(int $id, string $fileName): void
    {
        $this->filesystem->remove($this->getUploadPathForBook($id) . DIRECTORY_SEPARATOR . $fileName);
    }

    public function uploadBookFile(int $bookId, UploadedFile $file): string
    {
        $extension = $file->guessExtension();
        if (null === $extension) {
            throw new UploadFileInvalidTypeException();
        }

        $uniqueName = Uuid::v4()->toRfc4122() . '.' . $extension;
        $file->move($this->getUploadPathForBook($bookId), $uniqueName);

        return sprintf(self::LINK_BOOK_PATTERN, $bookId, $uniqueName);
    }

    private function getUploadPathForBook(int $id)
    {
        return $this->uploadDir . DIRECTORY_SEPARATOR . 'book' . DIRECTORY_SEPARATOR . $id;
    }
}
