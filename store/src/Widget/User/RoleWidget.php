<?php

declare(strict_types=1);


namespace App\Widget\User;


use App\Domain\User\Helper\RoleHelper;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RoleWidget extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('user_role', [$this, 'role'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    public function role(Environment $twig, string $role): string
    {
        $labels = RoleHelper::rolesList();
        return $twig->render('widget/users/role.html.twig', [
            'role' => $role,
            'labels' => $labels
        ]);
    }
}