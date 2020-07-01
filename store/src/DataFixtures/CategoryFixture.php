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
        $root = new Category();
        $root->setName('Root');
        $root->setUrl('root');

        $category_electronics = Category::create('Electronics', 'electronics', $root);

        $phones_category = Category::create('Phones', 'phones', $category_electronics);

        $ios_category = Category::create('iOS', 'ios', $phones_category);
        $android_category = Category::create('Android', 'android', $phones_category);

        $laptop_category = Category::create('Laptop', 'laptop', $category_electronics);

        $misc_category = Category::create('Misc', 'misc', $root);

        $adapters_category = Category::create('Adapters', 'adapters', $misc_category);

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