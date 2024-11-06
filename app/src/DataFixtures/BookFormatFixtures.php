<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Model\BookFormat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFormatFixtures extends Fixture
{
    final public function load(ObjectManager $manager): void
    {
        $format1 = (new BookFormat())
            ->setTitle('eBook')
            ->setDescription('Make accurate time series predictions with powerful pretrained foundation models!')
            ->setComment(null);

        $format2 = (new BookFormat())
            ->setTitle('print')
            ->setDescription('shipping optionsour return/exchange policy')
            ->setComment('In Time Series Forecasting Using Foundation Models you will discover');

        $manager->persist($format1);
        $manager->flush();
    }

    final public function getDependencies(): array
    {
        return [
            BookCategoryFixtures::class,
        ];
    }
}
