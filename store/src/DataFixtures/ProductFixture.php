<?php

declare(strict_types=1);


namespace App\DataFixtures;


use App\Domain\Product\Entity\Meta;
use App\Domain\Product\Entity\Price;
use App\Domain\Product\Entity\Product;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductFixture extends Fixture
{

    /**
     * @var SluggerInterface
     */
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $title = 'Тестовый товар';
        $product = Product::create(
            new DateTimeImmutable(),
            $title,
            $this->slugger->slug($title)->lower()->toString(),
            '123456789',
            new Price(1000, 2000),
            10,
            100,
            'Описание первого тестового товара, созданного автоматически',
            new Meta("Тестовый товар, созданный автоматически"),
            1
        );

        $manager->persist($product);
        $manager->flush();
    }
}