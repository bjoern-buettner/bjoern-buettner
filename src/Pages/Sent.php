<?php

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\MenuList;
use Twig\Environment;

class Sent
{
    public static function post(Environment $twig, string $lang): string
    {
        switch ($lang) {
            case 'en':
                return $twig->render('contact.twig', [
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
                    ]
                ]);
            case 'de':
                return $twig->render('contact.twig', [
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
                    ]
                ]);
        }
    }
}
