<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\User;

use Me\BjoernBuettner\Database;
use Me\BjoernBuettner\User;
use PDO;

class Factory
{
    public function create(): User
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        if (!$email || !$password) {
            return $_SESSION['user'] ?? new Anon();
        }
        $statement = Database::get()->prepare('SELECT * FROM `user` WHERE `email`=:email');
        $statement->execute([':email' => $email]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);
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