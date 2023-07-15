<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\User;

use Me\BjoernBuettner\User;

class Customer implements User
{
    public function __construct(
        private readonly int $id,
        private readonly int $customerId,
        private readonly string $name,
        private readonly string $email,
        private readonly array $roles,
    ) {
    }
    public function active(): bool
    {
        return true;
    }
    public function getCustomerId(): int
    {
        return $this->customerId;
    }
    public function mayAccessInvoices(): bool
    {
        return in_array('accounting', $this->roles, true);
    }
    public function mayAccessQuotes(): bool
    {
        return in_array('accounting', $this->roles, true);
    }
    public function mayAccessCases(): bool
    {
        return in_array('member', $this->roles, true);
    }
    public function mayAccessUsers(): bool
    {
        return in_array('admin', $this->roles, true);
    }
    public function mayAccessBlog(): bool
    {
        return false;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getEMail(): string
    {
        return $this->email;
    }
}
