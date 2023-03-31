<?php

use Dotenv\Dotenv;
use Me\BjoernBuettner\Database;
use PHPMailer\PHPMailer\PHPMailer;

require_once (__DIR__ . '/../vendor/autoload.php');

Dotenv::createImmutable(dirname(__DIR__))->load();

function make(): string
{
    $chars = str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
    $out = '';
    while (strlen($out) < 255) {
        $out .= $chars[rand(0, 61)];
    }
    return $out;
}

echo "The eMail:\n";
$email = trim(fgets(STDIN));
echo "The Given Name:\n";
$firstName = trim(fgets(STDIN));
echo "The Last Name:\n";
$lastName = trim(fgets(STDIN));
$password = make();
echo "The Role:\n";
$role = trim(fgets(STDIN));
Database::get()
    ->prepare('INSERT INTO `user` (`email`,`first_name`,`last_name`,`role`,`password`) VALUES (:email,:first,:last,:role,:password)')
    ->execute([
        ':email' => $email,
        ':first' => $firstName,
        ':last' => $lastName,
        ':role' => $role,
        ':password' => password_hash($password, PASSWORD_DEFAULT),
    ]);
$mailer = new PHPMailer();
$mailer->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
$mailer->addAddress($email, $firstName . ' ' . $lastName);
$mailer->Host = $_ENV['MAIL_HOST'];
$mailer->Username = $_ENV['MAIL_USERNAME'];
$mailer->Password = $_ENV['MAIL_PASSWORD'];
$mailer->Port = intval($_ENV['MAIL_PORT'], 10);
$mailer->CharSet = 'utf-8';
$mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mailer->Timeout = 60;
$mailer->Mailer ='smtp';
$mailer->Subject = 'Account created at bjoern-buettner.me';
$mailer->Body = "Your account at https://bjoern-buettner.me has been created.\nFor the login use your eMail and the Password $password.";
$mailer->SMTPAuth = true;
echo $mailer->smtpConnect() && $mailer->send() ? "Mail sent successfully." : "Failed to sent mail.";