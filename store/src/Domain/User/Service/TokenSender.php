<?php

declare(strict_types=1);


namespace App\Domain\User\Service;


use App\Domain\User\Entity\Email;
use App\Domain\User\Entity\ResetToken;
use RuntimeException;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment;

class TokenSender
{
    private $mailer;
    private $twig;

    public function __construct(Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    private function send(Email $email, string $subject, string $body): void
    {
        $message = (new Swift_Message($subject))
            ->setTo($email->getValue())
            ->setBody($body, 'text/html');

        if (!$this->mailer->send($message)) {
            throw new RuntimeException('Не удалось отправить письмо.');
        }
    }

    public function sendSignUpConfirm(Email $email, string $token): void
    {
        $subject = 'Подтверждение регистрации';
        $body = $this->twig->render('mail/user/signup.html.twig', [
            'confirm_url' => 'auth.signup.confirm',
            'token' => $token
        ]);
        $this->send($email, $subject, $body);
    }

    public function sendReset(Email $email, ResetToken $token): void
    {
        $subject = 'Сброс пароля';
        $body = $this->twig->render('mail/user/reset.html.twig', [
            'reset_url' => 'auth.reset.reset',
            'token' => $token->getToken()
        ]);
        $this->send($email, $subject, $body);
    }

}