<?php

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\Database;
use PDO;
use Twig\Environment;

class Dashboard
{
    public static function get(Environment $twig, string $lang): string
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
        $tickets = [];
        $invoices = [];
        $contracts = [];
        $members = [];
        if (null === $user['customer']) {
            $tickets = $database->query('SELECT * FROM ticket')->fetchAll(PDO::FETCH_ASSOC);
            $members = $database->query('SELECT * FROM `user` WHERE ISNULL(customer)')->fetchAll(PDO::FETCH_ASSOC);
            if ($user['role'] === 'Provider-Accounting' || $user['role'] === 'Provider-Administrator') {
                $invoices = $database->query('SELECT * FROM invoice')->fetchAll(PDO::FETCH_ASSOC);
                $contracts = $database->query('SELECT * FROM contract')->fetchAll(PDO::FETCH_ASSOC);
                foreach ($contracts as &$contract) {
                    $stmt = $database->prepare('SELECT * FROM contract_item WHERE contract=:contract');
                    $stmt->execute([':contract' => $contract['aid']]);
                    $contract['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $stmt = $database->prepare('SELECT * from customer WHERE aid=:aid');
                    $stmt->execute([':aid' => $contract['customer']]);
                    $contract['customer'] = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }
        } else {
            $stmt = $database->prepare('SELECT * FROM ticket WHERE customer=:customer');
            $stmt->execute([':customer' => $user['customer']]);
            $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt = $database->prepare('SELECT * FROM `user` WHERE customer=:customer');
            $stmt->execute([':customer' => $user['customer']]);
            $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($user['role'] === 'Customer-Accounting' || $user['role'] === 'Customer-Administrator') {
                $stmt = $database->prepare('SELECT * FROM invoice WHERE customer=:customer');
                $stmt->execute([':customer' => $user['customer']]);
                $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $stmt = $database->prepare('SELECT * FROM contract WHERE customer=:customer');
                $stmt->execute([':customer' => $user['customer']]);
                $contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($contracts as &$contract) {
                    $stmt = $database->prepare('SELECT * FROM contract_item WHERE contract=:contract');
                    $stmt->execute([':contract' => $contract['aid']]);
                    $contract['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            }
        }
        switch ($lang) {
            case 'en':
                return $twig->render('dashboard.twig', [
                    'title' => 'Customer Information Center',
                    'active' => '/dashboard',
                    'description' => 'Björn Büttner\'s Customer Information Center',
                    'content' => [
                        'title' => 'Dashboard',
                        'tickets' => 'Tickets',
                        'invoices' => 'Invoices',
                        'contracts' => 'Contracts',
                        'members' => 'Team-Members',
                    ],
                    'contracts' => $contracts,
                    'members' => $members,
                    'invoices' => $invoices,
                    'tickets' => $tickets,
                    'user' => $user,
                ]);
            case 'de':
                return $twig->render('dashboard.twig', [
                    'title' => 'Kundeninformationszentrum',
                    'active' => '/dashboard',
                    'description' => 'Björn Büttners Kundeninformationszentrum',
                    'content' => [
                        'title' => 'Dashboard',
                        'tickets' => 'Tickets',
                        'invoices' => 'Rechnungen',
                        'contracts' => 'Verträge',
                        'members' => 'Team-Mitglieder',
                    ],
                    'contracts' => $contracts,
                    'members' => $members,
                    'invoices' => $invoices,
                    'tickets' => $tickets,
                    'user' => $user,
                ]);
        }
    }
}
