<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\OfferList;
use Me\BjoernBuettner\TwigWrapper;
use Twig\Environment;

class Prices
{
    public function __construct(private readonly TwigWrapper $twig)
    {
    }

    public function get(string $lang): string
    {
        switch ($lang) {
            case 'en':
                return $this->twig->renderMinfied('prices.twig', [
                    'title' => 'Offers & Prices',
                    'active' => '/prices',
                    'description' => 'Offers by Björn Büttner in a well sorted overview',
                    'content' => [
                        'title' => 'My offers and prices',
                        'title2' => 'Offer details',
                        'reasoning' => 'The prices are primarily based on my interest and the relative effort of the
tasks. Individual prices will be provided on request.',
                        'prices' => 'All prices include taxes and assume remote work. Contract termination for monthly
offers is, if not agreed on differently, is 30 days until the end of the month.',
                    ],
                    'offers' => OfferList::get(),
                ], $lang);
            case 'de':
            default:
                return $this->twig->renderMinfied('prices.twig', [
                    'title' => 'Leistungen',
                    'active' => '/prices',
                    'description' => 'Leistungen von Björn Büttner in einem gut sortierten Überblick',
                    'content' => [
                        'title' => 'Meine Leistungen & Preise',
                        'title2' => 'Leistungsdetails',
                        'reasoning' => 'Die Preise orientieren sich primär an meinem Interesse und dem relativen Aufwand
der Tätigkeiten. Individuelle Angebote erhalten Sie auf Anfrage.',
                        'prices' => 'Alle Preise verstehen sich inklusive Steuern und setzen Remotearbeit voraus.
Kündigungsfrist für monatliche Angebote ist, falls nicht anders abgesprochen, 30 Tage zum Monatsende.',
                    ],
                    'offers' => OfferList::get(),
                ], $lang);
        }
    }
}
