<?php

namespace Me\BjoernBuettner\Pages;

use Twig\Environment;

class Home
{
    public static function get(Environment $twig, string $lang): string
    {
        switch ($lang) {
            case 'en':
                return $twig->render('home.twig', [
                    'title' => 'Home',
                    'active' => '/',
                    'description' => 'A bit about Björn Büttner, the founder of bjoern-buettner.me',
                    'content' => [
                        'title' => 'About me',
                        'paragraphs' => [
                            'As a software engineer with over ten years of work experience, I like to expand my knowledge and abilities by taking on additional projects in my time off work.',
                            'My absolute favourite topic are performance optimizations in collaboration with other developers. Not only does that provide better solution, it also increases all participant\'s knowledge.',
                            'If my favourite topic is not available, I spend time on quite a few other topics. NodeJS, Java, Browser-Extensions and Userscripts are among the projects I work on.',
                            'During that open source applications and libraries start their existence and sometimes some smaller tasks in software engineering and web-hosting are achieved.',
                            'I value and choose projects on their challenge and my interest in them, since this is just a side-job.',
                            'Obviously I don\'t do tasks that are in the same general sphere as my employer\'s, the JTL Software GmbH, business. I am not competing with my employer, please respect that in your inquieries.',
                        ],
                    ]
                ]);
            case 'de':
            default:
                return $twig->render('home.twig', [
                    'title' => 'Home',
                    'active' => '/',
                    'description' => 'Ein wenig über Björn Büttner, den Gründer von bjoern-buettner.me',
                    'content' => [
                        'title' => 'Über Mich',
                        'paragraphs' => [
                            'Als Softwareentwickler mit über zehn Jahren Berufserfahrung erweitere ich meine Kenntnisse und Fähigkeiten gerne durch weitere Projekte in meiner Freizeit.',
                            'Mein absolutes Lieblingsthema sind Performanceoptimierungen in Zusammenarbeit mit anderen Entwicklern. Hierbei entstehen nicht nur bessere Lösungen, sondern alle Beteiligten verbessern ihre eigenen Kenntnisse.',
                            'Sollte mein Lieblingsthema gerade nicht verfügbar sein, so beschäftige ich mich auch mit diversen anderen Themen. NodeJS, Java, Browser-Extensions und Userscripts sind nur einige der Projekte mit denen ich mich befasse.',
                            'Dabei entstehen sowohl Open-Source Anwendungen und Bibliotheken, als auch kleinere Dienstleistungen im Bereich Webhosting und Softwareentwicklung.',
                            'Projekte bewerte ich primär nach meinem Interesse an der jeweiligen Herausforderung, da es sich hierbei nur um eine Nebentätigkeit handelt.',
                            'Selbstverständlich trete ich nicht mit meinem derzeitigen Arbeitgeber, der JTL Software GmbH, in Konkurrenz, bitte beachten Sie dies bei Anfragen.',
                        ],
                    ]
                ]);
        }
    }
}
