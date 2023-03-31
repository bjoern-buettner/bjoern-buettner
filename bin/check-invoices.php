<?php

use Curl\Curl;
use Dotenv\Dotenv;
use Me\BjoernBuettner\Database;

require_once (__DIR__ . '/../vendor/autoload.php');

Dotenv::createImmutable(dirname(__DIR__))->load();

$database = Database::get();
$curl = new Curl();
$curl->setHeader('Content-Type', 'application/x-www-form-encoded');
$curl->setBasicAuthentication($_ENV['PAYPAL_CLIENT_ID'], $_ENV['PAYPAL_CLIENT_SECRET']);
$access = $curl->post($_ENV['PAYPAL_API_BASE'] . '/v1/oauth2/token', 'grant_type=client_credentials')->access_token;
foreach ($database->query('SELECT * FROM invoice WHERE ISNULL(payed)')->fetchAll(PDO::FETCH_ASSOC) as $invoice) {
    $curl = new Curl();
    $curl->setHeader('Authorization', "Bearer $access");
    $curl->setHeader('Content-Type', 'application/json');
    $status = $curl->get($_ENV['PAYPAL_API_BASE'] . "/v2/invoicing/invoices/{$invoice['paypal_id']}")->status;
    if ($status === 'PAID') {
        $database
            ->prepare('UPDATE invoice SET payed=NOW() WHERE aid=:aid')
            ->execute(['aid' => $invoice['aid']]);
    }
}