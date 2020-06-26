<?php

declare(strict_types=1);


namespace App\DataFixtures;


use App\Domain\Category\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{

    public const IOS_CATEGORY = 'category-ios';

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $root = Category::create("Root");
        $category_electronics = Category::create("Electronics");
        $category_electronics->setParent($root);

        $phones_category = Category::create('Phones');
        $phones_category->setParent($category_electronics);

        $ios_category = Category::create("iOS");
        $ios_category->setParent($phones_category);
        $android_category = Category::create("Android");
        $android_category->setParent($phones_category);

        $laptop_category = Category::create("Laptop");
        $laptop_category->setParent($category_electronics);

        $misc_category = Category::create("Misc");
        $misc_category->setParent($root);

        $adapters_category = Category::create("Adapters");
        $adapters_category->setParent($misc_category);

        $manager->persist($root);
        $manager->persist($category_electronics);
        $manager->persist($phones_category);
        $manager->persist($ios_category);
        $manager->persist($android_category);
        $manager->persist($laptop_category);
        $manager->persist($misc_category);
        $manager->persist($adapters_category);

        $manager->flush();

        $this->addReference(self::IOS_CATEGORY, $ios_category);
    }
}