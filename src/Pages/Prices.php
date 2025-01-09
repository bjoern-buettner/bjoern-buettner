<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\Entity\Category;
use Me\BjoernBuettner\Entity\Task;
use Me\BjoernBuettner\ObjectRelationshipMapping\Database;
use Me\BjoernBuettner\TextOutputBuilder;

class Prices
{
    public function __construct(private readonly TextOutputBuilder $twig, private readonly Database $database)
    {
    }

    public function loadOffers(): array
    {
        $data = [];
        foreach ($this->database->load(Category::class) as $category) {
            /** @var Category $category */
            $cat = [
                'de' => $category->getDe(),
                'en' => $category->getEn(),
                'chooseable' => false,
                'tasks' => []
            ];
            foreach ($this->database->load(Task::class, ['category' => $category['aid']]) as $task) {
                /** @var Task $task */
                $cat['tasks'][] = [
                    'title' => [
                        'en' => $task->getEnName(),
                        'de' => $task->getDeName(),
                    ],
                    'description' => [
                        'en' => $task->getEnDescription(),
                        'de' => $task->getDeDescription(),
                    ],
                    'price' => $task->getPrice(),
                    'fee_type' => $task->getFeeType(),
                ];
            }
            $data[] = $cat;
        }
        return $data;
    }
    public function get(string $lang): string
    {
        switch ($lang) {
            case 'en':
                return $this->twig->renderHTML('prices.twig', [
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
                    'offers' => $this->loadOffers(),
                ], $lang);
            case 'de':
            default:
                return $this->twig->renderHTML('prices.twig', [
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
                    'offers' => $this->loadOffers(),
                ], $lang);
        }
    }
}
