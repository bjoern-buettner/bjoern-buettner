<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\TextOutputBuilder;
use Me\BjoernBuettner\User;

class Login
{
    public function __construct(private readonly TextOutputBuilder $twig)
    {
    }
    public function get(string $lang): string
    {
        return $this->twig->renderHTML('login.twig', [
            'title' => $lang === 'en' ? 'Login' : 'Anmelden',
            'description' => $lang === 'en' ? 'Login to your account.' : 'Melde dich in deinem Account an.',
            'active' => '/login',
            'translations' => [
                'email' => 'eMail',
                'password' => $lang === 'en' ? 'Password' : 'Passwort',
                'sign_in' => $lang === 'en' ? 'Sign in' : 'Einloggen',
            ],
        ], $lang);
    }
    public function post(string $lang, User $user): string
    {
        $_SESSION['user'] = $user;
        session_write_close();
        header('Location: /' . $lang, true, 303);
        return '';
    }
}
