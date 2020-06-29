<?php

declare(strict_types=1);


namespace App\Domain\Category\Service;


use App\Domain\Category\Entity\Category;

class CategoryDecorator
{
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
}