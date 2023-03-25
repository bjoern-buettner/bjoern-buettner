<?php

namespace Me\BjoernBuettner;

class OfferList
{
    public static $offers = [
        [
            'en' => 'Software development',
            'de' => 'Softwareentwicklung',
            'chooseable' => true,
            'tasks' => [
                [
                    'title' => [
                        'de' => 'API-Benchmark Programmierung',
                        'en' => 'API-Benchmark coding',
                    ],
                    'description' => [
                        'de' => 'Auf Basis von @idrinth/api-bench für Rest-APIs.',
                        'en' => 'Based on @idrinth/api-bench for REST-APIs.',
                    ],
                    'price' => [
                        'de' => '110€/Stunde',
                        'en' => '110€/hour',
                    ],
                ],
                [
                    'title' => [
                        'de' => 'Dockerfile Konfiguration',
                        'en' => 'Dockerfile configuration',
                    ],
                    'description' => [
                        'de' => 'Erstellen, Erweitern oder Optimieren.',
                        'en' => 'Create, modify or optimise.',
                    ],
                    'price' => [
                        'de' => '90€/Stunde',
                        'en' => '90€/hour',
                    ],
                ],
                [
                    'title' => [
                        'de' => 'Open-API Dokumentation',
                        'en' => 'Open-API documentation',
                    ],
                    'description' => [
                        'de' => 'Erstellung anhand von Quellcode.',
                        'en' => 'Creation based on sourcecode.',
                    ],
                    'price' => [
                        'de' => '120€/Stunde',
                        'en' => '120€/hour',
                    ],
                ],
                [
                    'title' => [
                        'de' => 'Webseitenerstellung: Backend',
                        'en' => 'Website creation: Backend',
                    ],
                    'description' => [
                        'de' => 'Programmier- und Datenbankaufgaben.',
                        'en' => 'programming and database tasks',
                    ],
                    'price' => [
                        'de' => '90€/Stunde',
                        'en' => '90€/hour',
                    ],
                ],
                [
                    'title' => [
                        'de' => 'Webseitenerstellung: Frontend',
                        'en' => 'Website creation: Frontend',
                    ],
                    'description' => [
                        'de' => 'Styling und Javascriptentwicklung.',
                        'en' => 'Styling and javascript development.',
                    ],
                    'price' => [
                        'de' => '85€/Stunde',
                        'en' => '85€/hour',
                    ],
                ],
            ],
        ],
        [
            'de' => 'Administration',
            'en' => 'Administration',
            'chooseable' => true,
            'tasks' => [
                [
                    'title' => [
                        'en' => 'Linux-WebServer configuration',
                        'de' => 'Linux-WebServer Konfiguration',
                    ],
                    'description' => [
                        'en' => 'Apache2, PHP, NodeJS and/or MariaDB istallation and configuration.',
                        'de' => 'Apache2, PHP, NodeJS und/oder MariaDB installieren und konfigurieren.',
                    ],
                    'price' => [
                        'en' => '85€/hour',
                        'de' => '85€/Stunde',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Create eMail alias',
                        'de' => 'eMail Alias anlegen',
                    ],
                    'description' => [
                        'en' => 'For eMail inboxes already provided by me.',
                        'de' => 'Bei bereits von mit verwalteten eMail-Postfächern.',
                    ],
                    'price' => [
                        'en' => '5€/alias',
                        'de' => '5€/Alias',
                    ],
                ],
            ],
        ],
        [
            'de' => '(Web-) Hosting',
            'en' => '(Web) hosting',
            'chooseable' => true,
            'tasks' => [
                [
                    'title' => [
                        'en' => 'Static website',
                        'de' => 'Statische Webseite',
                    ],
                    'description' => [
                        'en' => 'A simple, static website on a domain to be provided by the customer.',
                        'de' => 'Eine einfache, statische Webseite auf vom Kunden zu stellender Domain.',
                    ],
                    'price' => [
                        'en' => '4€/month',
                        'de' => '4€/Monat',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Dynamic website',
                        'de' => 'Dynamische Webseite',
                    ],
                    'description' => [
                        'en' => 'A simple website based on Wordpress for example with attached database.',
                        'de' => 'Eine einfache Webseite auf zum Beispiel Wordpress-Basis mit angeschlossener Datenbank.',
                    ],
                    'price' => [
                        'en' => '7€/month',
                        'de' => '7€/Monat',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'E-mail inbox',
                        'de' => 'eMail Postfach',
                    ],
                    'description' => [
                        'en' => 'An e-mail inbox on a domain to be provided by the customer.',
                        'de' => 'Ein eMail-Postfach auf einer vom Kunden zu stellenden Domain.',
                    ],
                    'price' => [
                        'en' => '4€/month',
                        'de' => '4€/Monat',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Domain',
                        'de' => 'Domain',
                    ],
                    'description' => [
                        'en' => 'A domain based on your desired name and it\'s availability. Only as an add-on to another hosting service.',
                        'de' => 'Eine Domain nach Wunsch und Verfügbarkeit zusätzlich zu einer der anderen Dienstleistungen im Hostingbereich.',
                    ],
                    'price' => [
                        'en' => 'base price + 2€/month',
                        'de' => 'Basispreis + 2€/Monat',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Secrets Vault',
                        'de' => 'Secrets Vault',
                    ],
                    'description' => [
                        'en' => 'Hosting of a Secrets Vaults based on idrinth/walled-secrets. A domain must be provided.',
                        'de' => 'Hosting eines Secrets Vaults auf Basis von idrinth/walled-secrets. Eine Domain ist zu stellen.',
                    ],
                    'price' => [
                        'en' => '2€/person/month',
                        'de' => '2€/Person/Monat',
                    ],
                ],
            ],
        ],
        [
            'de' => 'Beratung',
            'en' => 'Consulting',
            'chooseable' => true,
            'tasks' => [
                [
                    'title' => [
                        'en' => 'Consulting',
                        'de' => 'Beratung',
                    ],
                    'description' => [
                        'en' => 'Via microsoft teams or similar software.',
                        'de' => 'Via Microsoft Teams oder vergleichbarer Software.',
                    ],
                    'price' => [
                        'en' => '135€/hour',
                        'de' => '135€/Stunde',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Performance analysis',
                        'de' => 'Performance-Analysen',
                    ],
                    'description' => [
                        'en' => 'With or without help from Application Performance Monitoring(APM).',
                        'de' => 'Mit oder ohne Hilfe vom Application Performance Monitoring(APM).',
                    ],
                    'price' => [
                        'en' => '130€/hour',
                        'de' => '130€/Stunde',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Codereview',
                        'de' => 'Codereview',
                    ],
                    'description' => [
                        'en' => 'Price includes the actual review and a written review result.',
                        'de' => 'Preis ist für das Codereview und einen resultierenden Bericht.',
                    ],
                    'price' => [
                        'en' => '90€/hour',
                        'de' => '90€/Stunde',
                    ],
                ],
            ],
        ],
        [
            'de' => 'Continuous Integration (CI)',
            'en' => 'Continuous Integration (CI)',
            'chooseable' => true,
            'tasks' => [
                [
                    'title' => [
                        'en' => 'Github-Actions',
                        'de' => 'Github-Actions',
                    ],
                    'description' => [
                        'en' => 'Creation, optimizing or modification of Github-Actions.',
                        'de' => 'Erstellung, Optimierung oder Anpassung von Github-Actions.',
                    ],
                    'price' => [
                        'en' => '95€/hour',
                        'de' => '95€/Stunde',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Gitlab-Pipelines',
                        'de' => 'Gitlab-Pipelines',
                    ],
                    'description' => [
                        'en' => 'Creation, optimizing or modification of Gitlab-Pipelines.',
                        'de' => 'Erstellung, Optimierung oder Anpassung von Gitlab-Pipelines.',
                    ],
                    'price' => [
                        'en' => '95€/hour',
                        'de' => '95€/Stunde',
                    ],
                ],
            ],
        ],
        [
            'de' => 'Training',
            'en' => 'Training',
            'chooseable' => true,
            'tasks' => [
                [
                    'title' => [
                        'en' => 'Basics of Javascript',
                        'de' => 'Grundlagen Javascript',
                    ],
                    'description' => [
                        'en' => 'Independant of the number of persons.',
                        'de' => 'Unabhängig von der Personenzahl.',
                    ],
                    'price' => [
                        'en' => '110€/hour',
                        'de' => '110€/Stunde',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Basics of MariaDB usage',
                        'de' => 'Grundlagen Nutzung MariaDB',
                    ],
                    'description' => [
                        'en' => 'Database design,queries and query optimisation independant of the number of persons.',
                        'de' => 'Datenbankdesign, Queries und Queryoptimierung unabhängig von der Personenzahl.',
                    ],
                    'price' => [
                        'en' => '125€/hour',
                        'de' => '125€/Stunde',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Basics of PHP',
                        'de' => 'Grundlagen PHP',
                    ],
                    'description' => [
                        'en' => 'Independant of the number of persons.',
                        'de' => 'Unabhängig von der Personenzahl.',
                    ],
                    'price' => [
                        'en' => '125€/hour',
                        'de' => '125€/Stunde',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Basics of Typescript',
                        'de' => 'Grundlagen Typescript',
                    ],
                    'description' => [
                        'en' => 'Independant of the number of persons.',
                        'de' => 'Unabhängig von der Personenzahl.',
                    ],
                    'price' => [
                        'en' => '115€/hour',
                        'de' => '115€/Stunde',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Unittesting',
                        'de' => 'Unittesting',
                    ],
                    'description' => [
                        'en' => 'Independant of the number of persons in PHP, Javascript, Typescript or Java.',
                        'de' => 'Unabhängig von der Personenzahl in PHP, Javascript, Typescript oder Java.',
                    ],
                    'price' => [
                        'en' => '125€/hour',
                        'de' => '125€/Stunde',
                    ],
                ],
            ],
        ],
        [
            'de' => 'Aufschläge',
            'en' => 'Extra charges',
            'chooseable' => false,
            'tasks' => [
                [
                    'title' => [
                        'en' => 'Missing Open-API documentation',
                        'de' => 'Fehlende Open-API Dokumentation',
                    ],
                    'description' => [
                        'en' => 'For API-clients and API performance benchmarks.',
                        'de' => 'Für API-Anbindungen und API-Performance-Benchmarks.',
                    ],
                    'price' => [
                        'en' => '160€',
                        'de' => '160€',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Missing test coverage',
                        'de' => 'Fehlende Testabdeckung',
                    ],
                    'description' => [
                        'en' => 'In case of unit test coverage of below 50% in the source code area to be modified.',
                        'de' => 'Bei Unittestabdeckung von unter 50% im zu verändernden Codebereich.',
                    ],
                    'price' => [
                        'en' => '75€',
                        'de' => '75€',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'No references',
                        'de' => 'Keine Referenz',
                    ],
                    'description' => [
                        'en' => 'The desired tasks may not be used as reference and the customer may not be named publicly.',
                        'de' => 'Die gewünschten Aufgaben dürfen nicht als Referenz genutzt werden und der Auftraggeber darf nicht öffentlich benannt werden.',
                    ],
                    'price' => [
                        'en' => '15€',
                        'de' => '15€',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Night shift',
                        'de' => 'Nachtarbeit',
                    ],
                    'description' => [
                        'en' => 'In case of work that has to be done at night.',
                        'de' => 'Bei Arbeiten, die nachts getätigt werden müssen.',
                    ],
                    'price' => [
                        'en' => '100€',
                        'de' => '100€',
                    ],
                ],
            ],
        ],
        [
            'de' => 'Rabatte',
            'en' => 'Discounts',
            'chooseable' => false,
            'tasks' => [
                [
                    'title' => [
                        'en' => 'Open-Source Coding',
                        'de' => 'Open-Source Entwicklung',
                    ],
                    'description' => [
                        'en' => 'For modifications of existing or creation of new open-source applications or libaries.',
                        'de' => 'Für Anpassungen an Existierenden oder Erstellung von neuen Open-Source-Anwendungen oder Bibliotheken.',
                    ],
                    'price' => [
                        'en' => '25€/hour',
                        'de' => '25€/Stunde',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Apprentice-Training',
                        'de' => 'Azubi-Training',
                    ],
                    'description' => [
                        'en' => 'For training only including apprentices.',
                        'de' => 'Bei einem ausschließlichen Training von Auszubildenden.',
                    ],
                    'price' => [
                        'en' => '15€/hour',
                        'de' => '15€/Stunde',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Open-Source-Support',
                        'de' => 'Open-Source-Support',
                    ],
                    'description' => [
                        'en' => 'For support requests regarding open-source applications or libraries created by me.',
                        'de' => 'Bei Supportanfragen zu von mir erstellten Open-Source-Anwendungen oder Bibliotheken.',
                    ],
                    'price' => [
                        'en' => '10€/hour',
                        'de' => '10€/Stunde',
                    ],
                ],
                [
                    'title' => [
                        'en' => '5 Secret Persons',
                        'de' => '5 Geheime Personen',
                    ],
                    'description' => [
                        'en' => 'For above or exactly 5 persons for a secret vault instance.',
                        'de' => 'Bei über oder genau 5 Personen für eine Secret-Vault-Instanz.',
                    ],
                    'price' => [
                        'en' => '1€/month',
                        'de' => '1€/Monat',
                    ],
                ],
                [
                    'title' => [
                        'en' => '25 Secret Persons',
                        'de' => '25 Geheime Personen',
                    ],
                    'description' => [
                        'en' => 'For above or exactly 25 persons for a secret vault instance.',
                        'de' => 'Bei über oder genau 25 Personen für eine Secret-Vault-Instanz.',
                    ],
                    'price' => [
                        'en' => '1€/month',
                        'de' => '1€/Monat',
                    ],
                ],
                [
                    'title' => [
                        'en' => '125 Secret Persons',
                        'de' => '125 Geheime Personen',
                    ],
                    'description' => [
                        'en' => 'For above or exactly 125 persons for a secret vault instance.',
                        'de' => 'Bei über oder genau 125 Personen für eine Secret-Vault-Instanz.',
                    ],
                    'price' => [
                        'en' => '1€/month',
                        'de' => '1€/Monat',
                    ],
                ],
            ],
        ],
    ];
}
