<?php

declare(strict_types=1);


namespace App\Domain\User\View;


class AuthView
{
    public $id;
    public $email;
    public $password_hash;
    public $role;
    public $status;
}