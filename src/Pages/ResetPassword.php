<?php

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\Database;
use PDO;
use Twig\Environment;

class ResetPassword
{
    public static function get(Environment $twig, string $lang): string
    {
        switch ($lang) {
            case 'en':
                return $twig->render('reset-password.twig', [
                    'title' => 'Reset Password',
                    'active' => '/reset-password',
                    'description' => 'Reset the customer information center password.',
                    'content' => [
                        'title' => 'Reset Password',
                        'password' => 'Password',
                        'change' => 'Change',
                    ],
                ]);
            case 'de':
                return $twig->render('reset-password.twig', [
                    'title' => 'Password zurücksetzen',
                    'active' => '/reset-password',
                    'description' => 'Password des Kundeninformationszentrums zurücksetzen.',
                    'content' => [
                        'title' => 'Password zurücksetzen',
                        'password' => 'Passwort',
                        'change' => 'Ändern',
                    ],
                ]);
        }
    }
    public static function post(Environment $twig, string $lang, array $params): string
    {
        if (!isset($_POST['pw1']) || !isset($_POST['pw2']) || $_POST['pw1'] !== $_POST['pw2']) {
            header('Location: /reset-password/'.$params['hash'].'/'.$params['key'].'/'.$lang);
            return '';
        }
        $database = Database::get();
        $stmt = $database->prepare('SELECT aid FROM `user` WHERE reset=:reset AND MD5(CONCAT(`aid`,`email`))=:md5');
        $stmt->execute([':reset' => $params['key'], ':md5' => $params['hash']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            header('Location: /forgot-password/'.$lang);
            return '';
        }
        $_SESSION['aid'] = $user['aid'];
        $database
            ->prepare('UPDATE `user` SET `password`=:pw WHERE aid=:aid')
            ->execute([':pw' => password_hash($_POST['pw1'], PASSWORD_DEFAULT), ':aid' => $user['aid']]);
        header('Location: /dashboard/'.$lang);
        return '';
    }
}
