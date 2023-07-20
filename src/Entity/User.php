<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Entity;

use Me\BjoernBuettner\Entity;

class User extends Entity
{
    public function __construct(
        ?int $aid,
        private readonly ?int $customer,
        private readonly ?int $teammember,
        private readonly string $name,
        private readonly string $email,
        private readonly string $password
    ) {
        parent::__construct($aid);
    }
    public function getCustomer(): ?int
    {
        return $this->customer;
    }
    public function getTeammember(): ?int
    {
        return $this->teammember;
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
