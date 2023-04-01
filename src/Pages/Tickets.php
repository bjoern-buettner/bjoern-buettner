<?php

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\Database;
use PDO;
use Twig\Environment;

class Tickets
{
    public static function detail(Environment $twig, string $lang, array $params): string
    {
        if (!isset($_SESSION['aid'])) {
            header('Location: /login/'.$lang, true, 303);
            return '';
        }
        $database = Database::get();
        $stmt = $database->prepare('SELECT * FROM `user` WHERE aid=:aid');
        $stmt->execute([':aid' => $_SESSION['aid']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            unset($_SESSION['aid']);
            header('Location: /login/'.$lang, true, 303);
            return '';
        }
        $stmt = $database->prepare('SELECT * FROM `ticket` WHERE aid=:aid');
        $stmt->execute([':aid' => $params['id']]);
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$ticket) {
            header('Location: /dashboard/'.$lang, true, 303);
            return '';
        }
        if ($user['organisation']!==null && $user['organisation']!==$ticket['customer']) {
            header('Location: /dashboard/'.$lang, true, 303);
            return '';
        }
        if (!isset($_FILES['file']) && !isset($_POST['content'])) {
            header('Location: /tickets/'.$params['id'].'/'.$lang, true, 303);
            return '';
        }
        if (isset($_FILES['file'])) {
            $content = file_get_contents($_FILES['file']['tmp_name']);
            $database
                ->prepare('INSERT IGNORE INTO ticket_attachment (`name`,`ticket`,`uploader`,`hash`,`data`,`mime`) VALUES(:name,:ticket,:uploader,:hash,:data,:mime)')
                ->execute([
                    ':name' => $_FILES['file']['name'],
                    ':ticket' => $params['id'],
                    ':uploader' => $user['aid'],
                    ':hash' => md5($content),
                    ':data' => $content,
                    ':mime' => $_FILES['file']['type'] ?? 'application/octet-stream'
                ]);
            header('Location: /tickets/'.$params['id'].'/'.$lang, true, 303);
            return '';
        }
        $database
            ->prepare('INSERT INTO ticket_comment (`ticket`,`ip`,`creator`,`content`) VALUES (:ticket,:ip,:creator,:content)')
            ->execute([
                ':ticket' => $ticket['aid'],
                ':ip' => $_SERVER['REMOTE_ADDR'],
                ':creator' => $user['aid'],
                ':content' => $_POST['content'],
            ]);
        header('Location: /tickets/'.$params['id'].'/'.$lang, true, 303);
        return '';
    }
    
    public static function get(Environment $twig, string $lang, array $params): string
    {
        if (!isset($_SESSION['aid'])) {
            header('Location: /login/'.$lang, true, 303);
            return '';
        }
        $database = Database::get();
        $stmt = $database->prepare('SELECT * FROM `user` WHERE aid=:aid');
        $stmt->execute([':aid' => $_SESSION['aid']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            unset($_SESSION['aid']);
            header('Location: /login/'.$lang, true, 303);
            return '';
        }
        $stmt = $database->prepare('SELECT * FROM `ticket` WHERE aid=:aid');
        $stmt->execute([':aid' => $params['id']]);
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$ticket) {
            header('Location: /dashboard/'.$lang, true, 303);
            return '';
        }
        if ($user['organisation']!==null && $user['organisation']!==$ticket['customer']) {
            header('Location: /dashboard/'.$lang, true, 303);
            return '';
        }
        $stmt = $database->prepare('SELECT * FROM ticket_comment INNER JOIN `user` ON `user`.aid=ticket_comment.creator WHERE ticket=:ticket');
        $stmt->execute([':ticket' => $ticket['aid']]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = $database->prepare('SELECT * FROM ticket_time INNER JOIN task ON task.aid=ticket_time.task WHERE ticket=:ticket');
        $stmt->execute([':ticket' => $ticket['aid']]);
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = $database->prepare('SELECT * FROM ticket_attachment WHERE ticket=:ticket');
        $stmt->execute([':ticket' => $ticket['aid']]);
        $attachments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        switch ($lang) {
            case 'en':
                return $twig->render('ticket.twig', [
                    'title' => $ticket['subject'],
                    'active' => '/ticket',
                    'description' => $ticket['subject'],
                    'content' => [
                        'tasks' => 'Work done',
                        'comment' => 'Comment',
                        'add' => 'Add',
                        'attachments' => 'Attachments',
                        'uploaded' => 'Uploaded',
                    ],
                    'comments' => $comments,
                    'tasks' => $tasks,
                    'user' => $user,
                    'ticket' => $ticket,
                    'attachments' => $attachments,
                ]);
            case 'de':
                return $twig->render('ticket.twig', [
                    'title' => $ticket['subject'],
                    'active' => '/ticket',
                    'description' => $ticket['subject'],
                    'content' => [
                        'tasks' => 'Geleistete Arbeit',
                        'comment' => 'Kommentar',
                        'add' => 'Hinzufügen',
                        'attachments' => 'Anhänge',
                        'uploaded' => 'Hochgeladen',
                    ],
                    'comments' => $comments,
                    'tasks' => $tasks,
                    'user' => $user,
                    'ticket' => $ticket,
                    'attachments' => $attachments,
                ]);
        }
    }
    
    public static function post(Environment $twig, string $lang): string
    {
        if (!isset($_SESSION['aid'])) {
            header('Location: /login/'.$lang, true, 303);
            return '';
        }
        $database = Database::get();
        $stmt = $database->prepare('SELECT * FROM `user` WHERE aid=:aid');
        $stmt->execute([':aid' => $_SESSION['aid']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            unset($_SESSION['aid']);
            header('Location: /login/'.$lang, true, 303);
            return '';
        }
        if ($user['organisation']===null) {
            header('Location: /dashboard/'.$lang, true, 303);
            return '';
        }
        $database
            ->prepare('INSERT INTO ticket (customer,subject) VALUES (:customer,:subject)')
            ->execute([':customer' => $user['organisation'], ':subject' => $_POST['subject']]);
        $ticket = $database->lastInsertId();
        $database
            ->prepare('INSERT INTO ticket_comment (`ticket`,`ip`,`creator`,`content`) VALUES (:ticket,:ip,:creator,:content)')
            ->execute([
                ':ticket' => $ticket,
                ':ip' => $_SERVER['REMOTE_ADDR'],
                ':creator' => $user['aid'],
                ':content' => $_POST['decription'],
            ]);
        $mailer = new PHPMailer();
        $mailer->setFrom($_ENV['TICKET_MAIL_FROM'], $_ENV['TICKET_MAIL_FROM_NAME']);
        $mailer->addAddress($_ENV['MAIL_TO'], $_ENV['MAIL_TO_NAME']);
        $mailer->Host = $_ENV['TICKET_MAIL_HOST'];
        $mailer->Username = $_ENV['TICKET_MAIL_USER'];
        $mailer->Password = $_ENV['TICKET_MAIL_PASSWORD'];
        $mailer->Port = intval($_ENV['TICKET_MAIL_PORT_IMAP'], 10);
        $mailer->CharSet = 'utf-8';
        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailer->Timeout = 60;
        $mailer->Mailer ='smtp';
        $mailer->Subject = 'BBTicket '.$ticket.': '.$_POST['subject'];
        $mailer->Body = $_POST['description'];
        $mailer->SMTPAuth = true;
        if (!$mailer->smtpConnect()) {
            error_log('Mailer failed smtp connect. ' . $mailer->ErrorInfo);
        } else if (!$mailer->send()) {
            error_log('Mailer failed sending mail. ' . $mailer->ErrorInfo);
        }
        header('Location: /tickets/'.$ticket.'/'.$lang, true, 303);
    }
}