<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Entity;

class User
{
    public function __construct(
        private ?int $id,
        private ?int $customerId,
        private ?int $teamMateId,
        private string $name,
        private string $email,
        private string $password
    ) {
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }
    public function getTeamMateId(): ?int
    {
        return $this->teamMateId;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
}
