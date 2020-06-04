<?php

declare(strict_types=1);


namespace App\Domain\User\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\GeneratedValue;
use DomainException;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user_users", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"email"}),
 *     @ORM\UniqueConstraint(columns={"reset_token_token"})
 * })
 */
class User
{
    /**
     * @ORM\Column
     * @ORM\Id
     * @GeneratedValue
     */
    private $id;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_date;

    /**
     * @var Email|null
     * @ORM\Column(type="user_user_email", nullable=true)
     */
    private $email;

    /**
     * @var Phone|null
     * @ORM\Column(type="user_user_phone", nullable=true)
     */
    private $phone;

    /**
     * @var string|null
     * @ORM\Column(type="string", name="password_hash")
     */
    private $passwordHash;

    /**
     * @var string|null
     * @ORM\Column(type="string", name="confirm_token", nullable=true)
     */
    private $confirmToken;

    /**
     * @var Name
     * @ORM\Embedded(class="Name")
     */
    private $name;

    /**
     * @var ResetToken|null
     * @ORM\Embedded(class="ResetToken", columnPrefix="reset_token_")
     */
    private $resetToken;

    /**
     * @var Status
     * @ORM\Column(type="user_user_status", length=16)
     */
    private $status;

    /**
     * @var Role
     * @ORM\Column(type="user_user_role", length=16)
     */
    private $role;

    private function __construct(DateTimeImmutable $created_date, Name $name)
    {
        $this->created_date = $created_date;
        $this->name = $name;
        $this->role = Role::user();
    }

    /**
     * Create user from admin
     * @param DateTimeImmutable $date
     * @param Name $name
     * @param Email $email
     * @param Phone $phone
     * @param string $hash
     * @return static
     */
    public static function create(DateTimeImmutable $date, Name $name, Email $email, Phone $phone, string $hash): self
    {
        $user = new self($date, $name);
        $user->email = $email;
        $user->phone = $phone;
        $user->passwordHash = $hash;
        $user->status = Status::active();
        return $user;
    }

    /**
     * Signup via fronted form
     * @param DateTimeImmutable $created_date
     * @param Name $name
     * @param Email $email
     * @param string $phone
     * @param string $hash
     * @param string $token
     * @return static
     */
    public static function signUpByEmail(DateTimeImmutable $created_date, Name $name, Email $email, Phone $phone, string $hash, string $token): self
    {
        $user = new self($created_date, $name);
        $user->email = $email;
        $user->phone = $phone;
        $user->passwordHash = $hash;
        $user->confirmToken = $token;
        $user->status = Status::wait();
        return $user;
    }

    public function confirmSignUp(): void
    {
        if (!$this->status->isWait()) {
            throw new DomainException('Пользователь уже подтвержден.');
        }

        $this->status = Status::active();
        $this->confirmToken = null;
    }

    public function edit(Name $name): void
    {
        $this->name = $name;
    }

    public function changeRole(Role $role): void
    {
        if ($this->role->isEqual($role)) {
            throw new DomainException('Эта роль уже установлена.');
        }
        $this->role = $role;
    }

    public function setStatus(Status $status): void
    {
        if ($this->status->isEqual($status)) {
            throw new DomainException('У пользователя уже установлен этот статус.');
        }
        $this->status = $status;
    }

    public function requestPasswordReset(ResetToken $token, DateTimeImmutable $date): void
    {
        if (!$this->status->isActive()) {
            throw new DomainException('Пользователь не активирован.');
        }
        if (!$this->email) {
            throw new DomainException('Не указан email.');
        }
        if ($this->resetToken && !$this->resetToken->isExpiredTo($date)) {
            throw new DomainException('Запрос уже отправлен.');
        }
        $this->resetToken = $token;
    }

    public function passwordReset(DateTimeImmutable $date, string $hash): void
    {
        if (!$this->resetToken) {
            throw new DomainException('Запрос не был создан.');
        }
        if ($this->resetToken->isExpiredTo($date)) {
            throw new DomainException('Время действия токена истекло.');
        }
        $this->passwordHash = $hash;
        $this->resetToken = null;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCreatedDate(): DateTimeImmutable
    {
        return $this->created_date;
    }

    public function getEmail(): ?Email
    {
        return $this->email;
    }

    public function getPhone(): ?Phone
    {
        return $this->phone;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getResetToken(): ?ResetToken
    {
        return $this->resetToken;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @ORM\PostLoad()
     */
    public function checkEmbeds(): void
    {
        if ($this->resetToken->isEmpty()) {
            $this->resetToken = null;
        }
    }
}