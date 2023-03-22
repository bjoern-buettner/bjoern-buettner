<?php

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\MenuList;
use Me\BjoernBuettner\OfferList;
use Twig\Environment;

class Prices
{
    public static function get(Environment $twig, string $lang): string
    {
        switch ($lang) {
            case 'en':
                return $twig->render('prices.twig', [
                    'title' => 'Offers',
                    'active' => '/prices',
                    'lang' => 'en',
                    'description' => 'Offers by Björn Büttner',
                    'menu' => MenuList::$en,
                    'content' => [
                        'title' => 'My offers and prices',
                        'reasoning' => 'The prices are primarily based on my interest and the relative effort of the tasks. Individual prices will be provided on request.',
                        'prices' => 'All prices include taxes and assume remote work. Contract termination for monthly offers is, if not agreed on differently, is 30 days until the end of the month.',
                    ],
                    'offers' => OfferList::$offers,
                ]);
            case 'de':
                return $twig->render('prices.twig', [
                    'title' => 'Leistungen',
                    'active' => '/prices',
                    'description' => 'Leistungen von Björn Büttner',
                    'lang' => 'de',
                    'menu' => MenuList::$de,
                    'content' => [
                        'title' => 'Meine Leistungen & Preise',
                        'reasoning' => 'Die Preise orientieren sich primär an meinem Interesse und dem relativen Aufwand der Tätigkeiten. Individuelle Angebote erhalten Sie auf Anfrage.',
                        'prices' => 'Alle Preise verstehen sich inklusive Steuern und setzen Remotearbeit vorraus. Kündigungsfrist für monatliche Angebote ist, falls nicht anders abgesprochen, 30 Tage zum Monatsende.',
                    ],
                    'offers' => OfferList::$offers,
                ]);
        }
    }
}
