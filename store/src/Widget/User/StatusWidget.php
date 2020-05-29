<?php

declare(strict_types=1);


namespace App\Widget\User;


use App\Domain\User\Helper\UserHelper;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StatusWidget extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('user_status', [$this, 'status'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    public function status(Environment $twig, string $status): string
    {
        $labels = UserHelper::statusList();
        return $twig->render('widget/users/status.html.twig', [
            'status' => $status,
            'labels' => $labels
        ]);
    }
}