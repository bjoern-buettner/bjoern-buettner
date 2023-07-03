<?php

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\Database;
use Parsedown;
use Twig\Environment;
use Twig\TwigFilter;

class Team
{
    public static function get(Environment $twig, string $lang): string
    {
        $parsedown = new Parsedown();
        $twig->addFilter(new TwigFilter('markdown', function ($markdown) use ($parsedown) {
            return $parsedown->text($markdown);
        }, ['is_safe' => ['html']]));
        $team = Database::get()->query('SELECT * FROM teammember')->fetchAll();
        switch ($lang) {
            case 'en':
                return $twig->render('team.twig', [
                    'title' => 'Team',
                    'active' => '/team',
                    'description' => 'A little bit about the team behind bjoern-buettner.me',
                    'content' => [
                        'title' => 'About us - the team',
                    ],
                    'team' => $team,
                ]);
            case 'de':
            default:
                return $twig->render('team.twig', [
                    'title' => 'Team',
                    'active' => '/team',
                    'description' => 'Ein wenig über das Team hinter bjoern-buettner.me',
                    'content' => [
                        'title' => 'Über Uns - Das Team',
                    ],
                    'team' => $team,
                ]);
        }
    }
    public static function image(Environment $twig, string $lang, array $args): string
    {
        if (!isset($args['slug']) || !preg_match('/^[a-z0-9-]+$/', $args['slug'])) {
            header('HTTP/1.1 404 Not Found', true, 404)   ;
            return '404 Not Found';
        }
        $path = __DIR__ . '/../../resources/team/' . $args['slug'] . '.jpg';
        header('Content-Type: image/jpeg', true, 200);
        if (!file_exists($path)) {
            return file_get_contents(__DIR__ . '/../../resources/team/placeholder.jpg');
        }
        return file_get_contents($path) ?: '';
    }
}
