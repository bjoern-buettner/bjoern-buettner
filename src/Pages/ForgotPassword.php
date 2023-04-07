<?php

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\Database;
use PDO;
use PHPMailer\PHPMailer\PHPMailer;
use Twig\Environment;

class ForgotPassword
{
    public static function get(Environment $twig, string $lang): string
    {
        switch ($lang) {
            case 'en':
                return $twig->render('forgot-password.twig', [
                    'title' => 'Forgot Password',
                    'active' => '/forgot-password',
                    'description' => 'Reset the customer information center password.',
                    'content' => [
                        'title' => 'Forgot Password',
                        'email' => 'email',
                        'request' => 'Request change',
                    ],
                ]);
            case 'de':
                return $twig->render('forgot-password.twig', [
                    'title' => 'Passwort vergessen',
                    'active' => '/forgot-password',
                    'description' => 'Password des Kundeninformationszentrums zurücksetzen.',
                    'content' => [
                        'title' => 'Passwort vergessen',
                        'email' => 'eMail',
                        'request' => 'Änderung anfragen',
                    ],
                ]);
        }
    }
    private static function make(): string
    {
        $chars = str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
        $out = '';
        while (strlen($out) < 255) {
            $out .= $chars[rand(0, 61)];
        }
        return $out;
    }
    public static function post(Environment $twig, string $lang): string
    {
        if (isset($_POST['email'])) {
            $database = Database::get();
            $stmt = $database->prepare('SELECT aid,first_name,last_name FROM `user` WHERE `email`=:email');
            $stmt->execute([':email' => $_POST['email']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$user) {
                $hash = md5($user['aid'] . $_POST['email']);
                $hash2 = self::make();
                $mailer = new PHPMailer();
                $mailer->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
                $mailer->addAddress($_POST['email'], $user['first_name'] . ' ' . $user['last_name']);
                $mailer->Host = $_ENV['MAIL_HOST'];
                $mailer->Username = $_ENV['MAIL_USERNAME'];
                $mailer->Password = $_ENV['MAIL_PASSWORD'];
                $mailer->Port = intval($_ENV['MAIL_PORT'], 10);
                $mailer->CharSet = 'utf-8';
                $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mailer->Timeout = 60;
                $mailer->Mailer ='smtp';
                $mailer->Subject = 'Password reset at bjoern-buettner.me';
                $mailer->Body = "If you want to reset your password, please use the following link: https://bjoern-buettner.me/password-reset/{$hash}/{$hash2}/{$lang}";
                $mailer->SMTPAuth = true;
                $mailer->smtpConnect() && $mailer->send();
                $database
                    ->prepare('UPDATE `user` SET `reset`=:reset WHERE aid=:aid')
                    ->execute([':aid' => $user['aid'], ':hash' => $hash2]);
            }
            header('Location: /'.$lang, true, 303);
            return '';
        }
        header('Location: /forgot-password/'.$lang, true, 303);
        return '';
    }
}
