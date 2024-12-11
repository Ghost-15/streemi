<?php

namespace App\Entity;

use App\Enum\AccountStatus;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 100)]
    private ?string $username = null;
    #[ORM\Column(length: 255)]
    private ?string $email = null;
    #[ORM\Column(type: 'string', enumType: AccountStatus::class)]
    private AccountStatus $accountStatus;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getAccountStatus(): AccountStatus
    {
        return $this->accountStatus;
    }

    public function setAccountStatus(AccountStatus $accountStatus): void
    {
        $this->accountStatus = $accountStatus;
    }

}
