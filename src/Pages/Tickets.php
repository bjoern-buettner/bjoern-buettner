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
            unset($_SESSION['aid']);
            header('Location: /dashboard/'.$lang, true, 303);
            return '';
        }
        if ($user['organisation']!==null && $user['organisation']!==$ticket['organisation']) {
            header('Location: /dashboard/'.$lang, true, 303);
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
            unset($_SESSION['aid']);
            header('Location: /dashboard/'.$lang, true, 303);
            return '';
        }
        if ($user['organisation']!==null && $user['organisation']!==$ticket['organisation']) {
            header('Location: /dashboard/'.$lang, true, 303);
            return '';
        }
        $stmt = $database->prepare('SELECT * FROM ticket_comment INNER JOIN `user` ON `user`.aid=ticket_comment.creator WHERE ticket=:ticket');
        $stmt->execute([':ticket' => $ticket['aid']]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = $database->prepare('SELECT * FROM ticket_time INNER JOIN task ON task.aid=ticket_time.task WHERE ticket=:ticket');
        $stmt->execute([':ticket' => $ticket['aid']]);
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                    ],
                    'comments' => $comments,
                    'tasks' => $tasks,
                    'user' => $user,
                    'ticket' => $ticket,
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
                    ],
                    'comments' => $comments,
                    'tasks' => $tasks,
                    'user' => $user,
                    'ticket' => $ticket,
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
        header('Location: /tickets/'.$ticket.'/'.$lang, true, 303);
    }
}
