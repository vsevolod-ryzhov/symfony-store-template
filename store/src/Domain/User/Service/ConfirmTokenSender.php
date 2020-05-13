<?php

declare(strict_types=1);


namespace App\Domain\User\Service;


use App\Domain\User\Entity\Email;
use RuntimeException;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment;

class ConfirmTokenSender
{
    private $mailer;
    private $twig;

    public function __construct(Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function send(Email $email, string $token): void
    {
        $message = (new Swift_Message('Подтверждение регистрации'))
            ->setTo($email->getValue())
            ->setBody($this->twig->render('mail/user/signup.html.twig', [
                'token' => $token
            ]), 'text/html');

        if (!$this->mailer->send($message)) {
            throw new RuntimeException('Не удалось отправить письмо.');
        }
    }
}