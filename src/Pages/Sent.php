<?php

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\MenuList;
use PHPMailer\PHPMailer\PHPMailer;
use Twig\Environment;

class Sent
{
    public static function post(Environment $twig, string $lang): string
    {
        $request = [
            'topic' => $_POST['request'] ?? '',
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['mail'] ?? '',
            'text' => $_POST['content'] ?? '',
        ];
        if (!$request['topic'] || !$request['name'] || !$request['email'] || !$request['text']) {
            header('Location: /contact/' . $lang, true, 303);
            return '';
        }
        $mailer = new PHPMailer();
        $mailer->setFrom('no-reply@bjoern-buettner.me', 'No Reply (bjoern-buettner)');
        $mailer->addAddress('service@bjoern-buettner.me', 'Björn Büttner (bjoern-buettner)');
        if (!$mailer->addReplyTo($request['email'], $request['name'])) {
            header('Location: /contact/' . $lang, true, 303);
            return '';
        }
        $mailer->Host = 'mail.jrvs.info';
        $mailer->Username = 'no-reply@bjoern-buettner.me';
        $mailer->Password = 'JcxW3dOLTbMPX6daDpezzeC73cibb1kM4va1GS5MFPIqn6uyhBHSw4aRrdlMILI9rGegkSbFrSP1QjaqOwNvywR7E7Ihz4NmlzfT5ATCubVtMAIIZa1ipdX4LzWG9ifCr7UB69qiMfunkYpf6fa0RVcOua4RA2pzzDIozww2hK6SP0cD4bziqZZHWz3qL9KiCht0IKAhB295QU8rDk2z4KI3MiAaUHzLxTekeDmHHoIRjWZZXyxXTQSTZ1CoSW3Y';
        $mailer->Port = 587;
        $mailer->CharSet = 'utf-8';
        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailer->Timeout = 60;
        $mailer->Mailer ='smtp';
        $mailer->Subject = $request['topic'];
        $mailer->Body = $twig->render('mail.twig', $request);
        $mailer->SMTPAuth = true;
        if (!$mailer->smtpConnect()) {
            error_log('Mailer failed smtp connect. ' . $mailer->ErrorInfo);
            header('Location: /contact/' . $lang, true, 303);
            return '';
        }
        if (!$mailer->send()) {
            error_log('Mailer failed sending mail. ' . $mailer->ErrorInfo);
            header('Location: /contact/' . $lang, true, 303);
            return '';
        }
        switch ($lang) {
            case 'en':
                return $twig->render('sent.twig', [
                    'title' => 'Contact',
                    'active' => '/contact',
                    'lang' => 'en',
                    'description' => 'Contact Björn Büttner',
                    'menu' => MenuList::$en,
                    'content' => [
                        'title' => 'Request sent',
                        'topic' => 'Request topic',
                        'name' => 'Your name',
                        'email' => 'Your e-mail',
                        'request' => 'Your request',
                        'thanks' => 'Thank you for your message. I will answer as soon as possible, usually within 24 hours.',
                    ],
                    'request' => $request,
                ]);
            case 'de':
                return $twig->render('sent.twig', [
                    'title' => 'Kontakt',
                    'active' => '/contact',
                    'description' => 'Anfrage an Björn Büttner senden',
                    'lang' => 'de',
                    'menu' => MenuList::$de,
                    'content' => [
                        'title' => 'Anfrage gestellt',
                        'topic' => 'Anfragethema',
                        'name' => 'Ihr Name',
                        'email' => 'Ihre eMail',
                        'request' => 'Ihre Anfrage',
                        'thanks' => 'Vielen Dank für Ihre Anfrage. Sie erhalten schnellstmöglich, meistens innerhalb von 24 Stunden, eine Antwort.',
                    ],
                    'request' => $request,
                ]);
        }
    }
}
