<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\User;

use Me\BjoernBuettner\User;

class Anon implements User
{
    public function active(): bool
    {
        return false;
    }
    public function mayAccessInvoices(): bool
    {
        return false;
    }
    public function mayAccessQuotes(): bool
    {
        return false;
    }
    public function mayAccessCases(): bool
    {
        return false;
    }
    public function mayAccessUsers(): bool
    {
        return false;
    }
    public function mayAccessBlog(): bool
    {
        return false;
    }
    public function getId(): int
    {
        return 0;
    }
    public function getName(): string
    {
        return '';
    }
    public function getEMail(): string
    {
        return '';
    }
}
