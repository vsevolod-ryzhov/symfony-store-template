<?php

declare(strict_types=1);


namespace App\Widget\User;


use Symfony\Component\Security\Core\Security;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TopUserMenuWidget extends AbstractExtension
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {

        $this->security = $security;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('top_user_menu', [$this, 'topUserMenu'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    public function topUserMenu(Environment $twig): string
    {
        $isLoggedIn = $this->security->getUser() ? true : false;
        return $twig->render('widget/users/top_user_menu.html.twig', [
            'isLoggedIn' => $isLoggedIn
        ]);
    }
}