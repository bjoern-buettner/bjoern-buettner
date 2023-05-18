<?php

use Curl\Curl;
use Dotenv\Dotenv;
use Me\BjoernBuettner\Database;
use Mpdf\Mpdf;
use PHPMailer\PHPMailer\PHPMailer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once (__DIR__ . '/../vendor/autoload.php');

Dotenv::createImmutable(dirname(__DIR__))->load();

$database = Database::get();
$curl = new Curl();
$curl->setHeader('Content-Type', 'application/x-www-form-encoded');
$curl->setBasicAuthentication($_ENV['PAYPAL_CLIENT_ID'], $_ENV['PAYPAL_CLIENT_SECRET']);
$access = $curl->post($_ENV['PAYPAL_API_BASE'] . '/v1/oauth2/token', 'grant_type=client_credentials')->access_token;
$environment = new Environment(new FilesystemLoader(dirname(__DIR__) . '/templates'));
foreach ($database->query('SELECT * FROM customer')->fetchAll(PDO::FETCH_ASSOC) as $customer) {
    $paypalItems = [];
    $twigItems = [];
    $total = 0;
    $stmt = $database->prepare('SELECT contract_item.*
FROM contract_item
INNER JOIN contract ON contract.aid=contract_item.contract
WHERE contract.customer=:customer
AND contract.`start`>=DATE(NOW())
AND (ISNULL(contract.`end`) OR contract.`end`>=DATE(NOW()))');
    $stmt->execute([':customer' => $customer['aid']]);
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $contract) {
        $paypalItems[] = [
            'day' => date('Y-m-d'),
            'price' => $contract['price'],
            'fee_type' => 'monthly',
            'name' => $contract['name'],
            'description' => $contract['description'],
            'amount' => 1,
        ];
        $twigItems[] = [
            'day' => date('Y-m-d'),
            'price' => $contract['price'],
            'fee_type' => 'monthly',
            'name' => $contract['name'],
            'description' => $contract['description'],
            'amount' => 1,
        ];
        $total += $contract['price'];
    }
    $stmt = $database->prepare('SELECT task.*,ticket_time.amount, ticket_time.ticket,ticket_time.free,ticket_time.task,ticket_time.`day`
FROM ticket_time
INNER JOIN ticket ON ticket.aid=ticket_time.ticket
INNER JOIN task ON task.aid = ticket_time.task
WHERE ticket.customer=:customer
AND ISNULL(ticket_time.invoiced)');
    $stmt->execute([':customer' => $customer['aid']]);
    $toUpdate = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $task) {
        if ($task['free'] === '0') {
            if (!isset($paypalItems['t' . $task['task']])) {
                $paypalItems['t' . $task['task']] = [
                    'day' => date('Y-m-d'),
                    'price' => $task['free'] === '1' ? '0' : $task['price'],
                    'fee_type' => $task['fee_type'],
                    'name' => $customer['lang'] === 'en' ? $task['en_name'] : $task['de_name'],
                    'description' => $customer['lang'] === 'en' ? $task['en_description'] : $task['de_description'],
                    'amount' => $task['amount'],
                    'tickets' => [
                        '#' . $task['ticket'] . ' ' . $task['day'],
                    ],
                ];
            } else {
                $paypalItems['t' . $task['task']]['amount'] += $task['amount'];
                $paypalItems['t' . $task['task']]['tickets'][] = '#' . $task['ticket'] . ' ' . $task['day'];
            }
        }
        $twigItems[] = [
            'price' => $task['free'] === '1' ? '0' : $task['price'],
            'fee_type' => $task['fee_type'],
            'name' => ($customer['lang'] === 'en' ? $task['en_name'] : $task['de_name']) . ' #' . $task['ticket'],
            'description' => $customer['lang'] === 'en' ? $task['en_description'] : $task['de_description'],
            'amount' => $task['amount'],
            'day' => $task['day'],
        ];
        $toUpdate[] = $task['aid'];
        $total += $task['amount'] * ($task['free'] === '1' ? '0' : $task['price']);
    }
    if ($total > 0) {
        $curl = new Curl();
        $curl->setHeader('Authorization', "Bearer $access");
        $curl->setHeader('Content-Type', 'application/json');
        $invoicenumber = $curl->post($_ENV['PAYPAL_API_BASE'] . '/v2/invoicing/generate-next-invoice-number')->invoice_number;
        $curl = new Curl();
        $curl->setHeader('Authorization', "Bearer $access");
        $curl->setHeader('Content-Type', 'application/json');
        $id = $curl->post($_ENV['PAYPAL_API_BASE'] . '/v2/invoicing/invoices', json_encode([
            "detail" => [
                "invoice_number" => $invoicenumber,
                "invoice_date" => date('Y-m-d'),
                "currency_code" => "EUR",
                "payment_term" => [
                    "term_type" => "NET_10",
                    "due_date" => date('Y-m-d', strtotime('now + 10days')),
                ],
            ],
            "invoicer" => [
                "name" => [
                    "given_name" => "Björn",
                    "surname" => "Büttner",
                ],
                "address" => [
                    "address_line_1" => "Böllerts Höfe 4",
                    "address_line_2" => "",
                    "admin_area_2" => "Mülheim an der Ruhr",
                    "admin_area_1" => "Nordrhein-Westfalen",
                    "postal_code" => "45479",
                    "country_code" => "DE"
                ],
                "email_address" => "service@bjoern-buettner.me",
                "phones" => [],
                "website" => "bjoern-buettner.me",
                "tax_id" => "ABcNkWSfb5ICTt73nD3QON1fnnpgNKBy- Jb5SeuGj185MNNw6g",
                "logo_url" => "https://bjoern-buettner.me/logo.png",
            ],
            "primary_recipients" => [
                [
                    "billing_info" => [
                        "name" => [
                            "given_name" => $customer['first_name'],
                            "surname" => $customer['last_name']
                        ],
                        "address" => [
                            "address_line_1" => explode("\n", $customer['address'])[0],
                            "address_line_2" => explode("\n", $customer['address'])[1] ?? '',
                            "admin_area_1" => $customer['province'],
                            "admin_area_2" => $customer['city'],
                            "postal_code" => $customer['zip'],
                            "country_code" => $customer['country_code'],
                        ],
                        "email_address" => $customer['email'],
                    ],
                ],
            ],
            "items" => array_values(array_map(function(array $task): array {
                return [
                    'name' => $task['name'],
                    'quantity' => $task['amount'],
                    'unit_amount' => $task['price'],
                    'description' => $task['description'] . "\n" . implode("\n", $task['tickets']),
                    'tax' => 0,
                    'item_date' => $task['day'],
                    'unit_of_measure' => 'AMOUNT',
                ];
            }, $paypalItems)),
            "configuration" => [
                "partial_payment" => [
                    "allow_partial_payment" => false,
                ],
                "allow_tip" => true,
                "tax_calculated_after_discount" => true,
                "tax_inclusive" => true,
            ],
        ]))->id;
        $curl = new Curl();
        $curl->setHeader('Authorization', "Bearer $access");
        $curl->setHeader('Content-Type', 'application/json');
        $html = $customer['language'] === 'en' ? $environment->render('invoice-en', [
            'year' => date('Y'),
            'invoicenumber' => $invoicenumber,
            'id' => $id,
            'created' => date('Y-m-d'),
            'due' => date('Y-m-d', strtotime('now + 10days')),
            'total' => $total,
            'items' => $twigItems,
            'customer' => $customer,
            'qrcode' => $curl->post($_ENV['PAYPAL_API_BASE'] . "/v2/invoicing/invoices/{$id}/generate-qr-code", '{"width":150,"height":150}'),
        ]) : $environment->render('invoice-de', [
            'year' => date('Y'),
            'invoicenumber' => $invoicenumber,
            'id' => $id,
            'created' => date('d.m.Y'),
            'due' => date('d.m.Y', strtotime('now + 10days')),
            'total' => $total,
            'items' => $twigItems,
            'customer' => $customer,
            'qrcode' => $curl->post($_ENV['PAYPAL_API_BASE'] . "/v2/invoicing/invoices/{$id}/generate-qr-code", '{"width":150,"height":150}'),
        ]);
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        $pdf = $mpdf->OutputBinaryData();
        $database
            ->prepare('INSERT INTO invoice (`id`,`year`,`customer`,`paypal_id`,`total`,`pdf`) VALUES (:id,:year,:customer,:paypal_id,:total,:pdf)')
            ->execute([
                ':id' => $invoicenumber,
                ':year' => date('Y'),
                ':customer' => $customer['aid'],
                ':paypal_id' => $id,
                ':total' => $total,
                ':pdf' => $pdf,
            ]);
        $invoice = $database->lastInsertId();
        foreach ($toUpdate as $aid) {
            $database
                ->prepare('UPDATE task SET invoiced=:id WHERE aid=:aid')
                ->execute([
                    ':id' => $invoice,
                    ':aid' => $aid,
                ]);
        }
        foreach ($twigItems as $item) {
            $database
                ->prepare('INSERT INTO invoice_item (`invoice`,`price`,`total`,`amount`,`description`,`name`,`fee_type`) VALUES (:invoice,:price,:total,:amount,:description,:name,:fee_type)')
                ->execute([
                    ':invoice' => $invoice,
                    ':price' => $item['price'],
                    ':total' => $item['price'] * $item['amount'],
                    ':amount' => $item['amount'],
                    ':description' => $item['description'],
                    ':name' => $item['name'],
                    ':fee_type' => $item['fee_type'],
                    ':day' => $item['day'],
                ]);
        }
        $curl = new Curl();
        $curl->setHeader('Authorization', "Bearer $access");
        $curl->setHeader('Content-Type', 'application/json');
        $curl->post($_ENV['PAYPAL_API_BASE'] . "/v2/invoicing/invoices/{$id}/send", '{"send_to_invoicer":true,"send_to_recipient":false}');
        $mailer = new PHPMailer();
        $mailer->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
        $mailer->addReplyTo($_ENV['MAIL_TO'], $_ENV['MAIL_TO_NAME']);
        $mailer->addAddress($customer['email'], $customer['company_name'] ?: $customer['first_name'].' '.$customer['last_name']);
        $stmt = $database->prepare('SELECT email,first_name,last_name FROM `user` WHERE customer=:id AND `role` IN ("Customer-Administrator","Customer-Accounting") AND email<>:email');
        $stmt->execute([':id' => $customer['aid'], ':email' => $customer['email']]);
        foreach ($stmt->fetchAll() as $contact) {
            $mailer->addCC($contact['email'], $customer['first_name'].' '.$customer['last_name']);
        }
        $mailer->addBCC($_ENV['MAIL_TO'], $_ENV['MAIL_TO_NAME']);
        $mailer->Host = $_ENV['MAIL_HOST'];
        $mailer->Username = $_ENV['MAIL_USERNAME'];
        $mailer->Password = $_ENV['MAIL_PASSWORD'];
        $mailer->Port = intval($_ENV['MAIL_PORT'], 10);
        $mailer->CharSet = 'utf-8';
        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailer->Timeout = 60;
        $mailer->Mailer ='smtp';
        $mailer->Subject = $request['topic'];
        $mailer->Body = $html;
        $filename = tempnam(sys_get_temp_dir(), 'invoice-');
        file_put_contents($filename, $pdf);
        $mailer->addAttachment($filename, $customer['language'] === 'en' ? "Invoice-$invoicenumber.pdf" : "Rechnung-$invoicenumber.pdf");
        $mailer->SMTPAuth = true;
        if (!$mailer->smtpConnect()) {
            error_log('Mailer failed smtp connect. ' . $mailer->ErrorInfo);
        } elseif (!$mailer->send()) {
            error_log('Mailer failed sending mail. ' . $mailer->ErrorInfo);
        }
        unlink($filename);
    }
}