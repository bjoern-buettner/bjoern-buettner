<?php

namespace Me\BjoernBuettner\Pages;

class Invoices
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
        $stmt = $database->prepare('SELECT * FROM `invoice` WHERE aid=:aid');
        $stmt->execute([':aid' => $params['id']]);
        $invoice = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$invoice) {
            header('Location: /dashboard/'.$lang, true, 303);
            return '';
        }
        if ($user['organisation']!==null && $user['organisation']!==$invoice['customer']) {
            header('Location: /dashboard/'.$lang, true, 303);
            return '';
        }
        header('Content-Type: application/pdf');
        return $invoice['pdf'];
    }
}
