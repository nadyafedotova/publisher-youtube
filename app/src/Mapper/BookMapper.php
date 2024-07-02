<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Entity\Book;
use App\Model\BookDetails;
use App\Model\BookListItem;

class BookMapper
{
    public static function map(Book $book, BookDetails|BookListItem $model): BookDetails|BookListItem
    {
        $model->setId($book->getId());
        $model->setTitle($book->getTitle());
        $model->setSlug($book->getSlug());
        $model->setImage($book->getImage());
        $model->setAuthors($book->getAuthors());
        $model->setMeap($book->isMeap());
        $model->setPublicationDate($book->getPublicationDate()->getTimestamp());

        return $model;
    }
}
