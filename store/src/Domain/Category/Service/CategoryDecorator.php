<?php

declare(strict_types=1);


namespace App\Domain\Category\Service;


use App\Domain\Category\Entity\Category;

class CategoryDecorator
{
    /**
     * Get name of category with all parent categories names
     * @param Category $category
     * @param string $separator
     * @param bool $reverse
     * @return string
     */
    public static function getFullName(Category $category, string $separator = " / ", $reverse = false): string
    {
        $names = [];
        $names[] = $category->getName();
        $parent = $category->getParent();
        while ($parent) {
            // exclude root level
            if ($parent->getParent()) {
                $names[] = $parent->getName();
            }
            $parent = $parent->getParent();
        }

        if ($reverse) {
            $names = array_reverse($names);
        }

        return implode($separator, $names);
    }

    /**
     * Get human readable categories list
     * @param array $categories
     * @return array
     */
    public static function listPrettyPrint(array $categories): array
    {
        $list = [];
        foreach ($categories as $category) {
            $list[$category['id']] = str_repeat('-', $category['lvl']) . ' ' . $category['name'];
        }

        return $list;
    }
}