<?php

namespace Me\BjoernBuettner;

interface User
{
    public function active(): bool;
    public function getId(): int;
    public function getName(): string;
    public function mayAccessInvoices(): bool;
    public function mayAccessQuotes(): bool;
    public function mayAccessCases(): bool;
    public function mayAccessUsers(): bool;
    public function mayAccessBlog(): bool;
    public function getEMail(): string;
}
