<?php

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\MenuList;
use Me\BjoernBuettner\OfferList;
use Twig\Environment;

class Contact
{
    public static function get(Environment $twig, string $lang): string
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
                        'title' => 'Contact me',
                        'topic' => 'Request topic',
                        'please_choose' => 'Please choose',
                        'name' => 'Your name',
                        'email' => 'Your e-mail',
                        'request' => 'Your request',
                        'submit' => 'Submit',
                        'linkedin' => 'If your request is not business related, please use Linkedin for contacting me instead.',
                        'open_source' => 'If the request is related to one of my open-source projects and can be posted publicly, please use an issue in the related github repository for free support.'
                    ],
                    'offers' => OfferList::get(),
                ]);
            case 'de':
                return $twig->render('contact.twig', [
                    'title' => 'Kontakt',
                    'active' => '/contact',
                    'description' => 'Anfrage an Björn Büttner senden',
                    'lang' => 'de',
                    'menu' => MenuList::$de,
                    'content' => [
                        'title' => 'Anfrage stellen',
                        'topic' => 'Anfragethema',
                        'please_choose' => 'Bitte wählen',
                        'name' => 'Ihr Name',
                        'email' => 'Ihre eMail',
                        'request' => 'Ihre Anfrage',
                        'submit' => 'Absenden',
                        'linkedin' => 'Sollte sich Ihre Anfrage nicht um gewerbliche Themen drehen, so nutzen Sie bitte Linkedin für die Kontaktaufnahme.',
                        'open_source' => 'Handelt es sich um öffentlich postbare Supportanfragen für Open-Source-Projekte von mir, so nutzen Sie bitte das entsprechende Github-Repository für kostenlosen Support.',
                    ],
                    'offers' => OfferList::get(),
                ]);
        }
    }
}
