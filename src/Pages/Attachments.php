<?php

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\Database;
use PDO;
use Twig\Environment;

class Attachments
{ 
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
        $stmt = $database->prepare('SELECT * FROM `ticket_attachment` WHERE aid=:aid');
        $stmt->execute([':aid' => $params['id']]);
        $attachment = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$attachment) {
            header('Location: /dashboard/'.$lang, true, 303);
            return '';
        }
        $stmt = $database->prepare('SELECT * FROM `ticket` WHERE aid=:aid');
        $stmt->execute([':aid' => $attachment['ticket']]);
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$ticket) {
            header('Location: /dashboard/'.$lang, true, 303);
            return '';
        }
        if ($user['organisation']!==null && $user['organisation']!==$ticket['customer']) {
            header('Location: /dashboard/'.$lang, true, 303);
            return '';
        }
        header('Content-Type: '.$attachment['mime'], true);
        header('Content-Disposition: attachment; filename="' . $attachment['name'] . '"');
        return $attachment['data'];
    }
}
