<?php

declare(strict_types=1);


namespace App\Domain\Category\Service;


use App\Domain\Category\Entity\Category;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CategoryDecorator
{
    /**
     * Get name of category with all parent categories names
     * @param ?Category $category
     * @param string $separator
     * @param bool $reverse
     * @return string
     */
    public static function getFullName(?Category $category, string $separator = " / ", $reverse = false): string
    {
        if ($category === null) {
            return '';
        }

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

    public static function getHierarchyForSorting(NestedTreeRepository $repository, Category $root) {
        return $repository->childrenHierarchy(
            $root,
            false,
            [
                'decorate' => true,
                'rootOpen' => '<ul class="nested-sortable nested-list-group">',
                'nodeDecorator' => static function ($node)
                {
                    return "<span><i class=\"fa fa-arrows\"></i> $node[name]</span>";
                },
                'childOpen' => static function ($node)
                {
                    return "<li data-id=\"$node[id]\">";
                }
            ],
            false
        );
    }
}