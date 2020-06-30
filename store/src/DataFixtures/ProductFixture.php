<?php

declare(strict_types=1);


namespace App\DataFixtures;


use App\Domain\Category\Entity\Category;
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
        /* @var $product_category Category */
        $product_category = $this->getReference(CategoryFixture::IOS_CATEGORY);

        $name = 'Тестовый товар';
        $product = Product::create(
            new DateTimeImmutable(),
            $name,
            $this->slugger->slug($name)->lower()->toString(),
            '123456789',
            new Price(1000, 2000),
            10,
            100,
            'Описание первого тестового товара, созданного автоматически',
            new Meta("Тестовый товар, созданный автоматически"),
            1,
            $product_category
        );

        $manager->persist($product);
        $manager->flush();
    }
}