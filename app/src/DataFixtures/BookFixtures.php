<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Book;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture implements DependentFixtureInterface
{
    final public function load(ObjectManager $manager): void
    {
        $androidCategory = $this->getReference(BookCategoryFixtures::ANDROID_CATEGORY);
        $devicesCategory = $this->getReference(BookCategoryFixtures::DEVICES_CATEGORY);

        $book = new Book();
        $book->setTitle('RxJava for Android Developers');
        $book->setPublicationDate(new DateTimeImmutable('2019-04-01'));
        $book->setMeap(false);
        $book->setIsbn('123321');
        $book->setDescription('RxJava for Android Developers');
        $book->setAuthors(['Timo Tuominen']);
        $book->setSlug('rxjava-for-android-developers');
        $book->setCategories(new ArrayCollection([$androidCategory, $devicesCategory]));
        $book->setImage('https://images.manning.com/360/480/resize/book/b/bc57fb7-b239-4bf5-bbf2-886be8936951/Tuominen-RxJava-HI.png');

        $manager->persist($book);
        $manager->flush();
    }

    final public function getDependencies(): array
    {
        return [
            BookCategoryFixtures::class,
        ];
    }
}
