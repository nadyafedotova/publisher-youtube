<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\BookCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookCategoryFixtures extends Fixture
{
    final public const string ANDROID_CATEGORY = 'android';

    final public const string DEVICES_CATEGORY = 'devices';

    final public function load(ObjectManager $manager): void
    {
        $books = new BookCategory();
        $books->setTitle('Devices')->setSlug('devices');
        $devices = $books;
        $books->setTitle('Android')->setSlug('android');
        $android = $books;
        $categories = [
            self::DEVICES_CATEGORY => $devices,
            self::ANDROID_CATEGORY => $android,
        ];

        foreach ($categories as $category) {
            $manager->persist($category);
        }

        $manager->flush();

        foreach ($categories as $code => $category) {
            $this->addReference($code, $category);
        }
    }
}
