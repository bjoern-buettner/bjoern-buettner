<?php

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\Database;
use PDO;
use Twig\Environment;

class Login
{
    public static function post(Environment $twig, string $lang): string
    {
        if (isset($_SESSION['aid'])) {
            header('Location: /dashboard/'.$lang, true, 303);
            return '';
        }
        $stmt = Database::get()->prepare('SELECT aid,password FROM `user` WHERE email=:email');
        $stmt->execute([':email' => $_POST['email']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            header('Location: /login/'.$lang, true, 303);
            return '';
        }
        if (!password_verify($_POST['password'], $user['password'])) {
            header('Location: /login/'.$lang, true, 303);
            return '';
        }
        $_SESSION['aid'] = intval($user['aid'], 10);
        header('Location: /dashboard/'.$lang, true, 303);
        return '';
    }
    public static function get(Environment $twig, string $lang): string
    {
        if (isset($_SESSION['aid'])) {
            header('Location: /dashboard/'.$lang, true, 303);
            return '';
        }
        switch ($lang) {
            case 'en':
                return $twig->render('login.twig', [
                    'title' => 'Login',
                    'active' => '/login',
                    'description' => 'Login to the customer information center of Björn Büttner',
                    'content' => [
                        'title' => 'Login',
                        'registration' => 'If you are not a customer or don\'t have a login yet, please use the contact form to request one.',
                        'email' => 'eMail',
                        'password' => 'Password',
                        'submit' => 'Sign in',
                    ],
                ]);
            case 'de':
                return $twig->render('login.twig', [
                    'title' => 'Anmeldung',
                    'active' => '/login',
                    'description' => 'Am Kundeninformationszentrum von Björn Büttner anmelden',
                    'content' => [
                        'title' => 'Anmeldung',
                        'registration' => 'Sollten Sie noch kein Kunde sein oder noch keinen Login besitzen, melden Sie sich bitte über das Kontaktformular.',
                        'email' => 'eMail',
                        'password' => 'Passwort',
                        'submit' => 'Anmelden',
                    ],
                ]);
        }
    }
}
