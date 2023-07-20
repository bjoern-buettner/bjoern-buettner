<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\User;

use Me\BjoernBuettner\ObjectRelationshipMapping\Database;
use Me\BjoernBuettner\User;

class Factory
{
    public function __construct(
        private readonly Database $database
    ) {
    }
    public function get(): User
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        if (!$email || !$password) {
            return $_SESSION['user'] ?? new Anon();
        }
        $user = $this->database->load(
            \Me\BjoernBuettner\Entity\User::class,
            ['email' => $email]
        )[0] ?? null;
        if (!$user) {
            return $_SESSION['user'] ?? new Anon();
        }
        if (!password_verify($password, $user['password'])) {
            return $_SESSION['user'] ?? new Anon();
        }
        if (str_ends_with($email, '@bjoern-buettner.me')) {
            return $_SESSION['user'] = new Provider(
                (int) $user['id'],
                (int) $user['teamMateId'],
                $user['name'],
                $user['email'],
                explode(',', $user['roles'])
            );
        }
        return $_SESSION['user'] = new Customer(
            (int) $user['id'],
            (int) $user['customerId'],
            $user['name'],
            $user['email'],
            explode(',', $user['roles'])
        );
    }
}
