<?php

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\Database;
use Twig\Environment;

class Team
{
    public static function get(Environment $twig, string $lang): string
    {
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
    public function image(Environment $twig, string $lang, array $args): string
    {
        if (!isset($args['slug']) || !preg_match('/^[a-z0-9-]+$/', $args['slug'])) {
            header('HTTP/1.1 404 Not Found', true, 404)   ;
            return '404 Not Found';
        }
        $path = __DIR__ . '/../../resources/team/' . $args['slug'] . '.jpg';
        if (!file_exists($path)) {
            header('HTTP/1.1 404 Not Found', true, 404)   ;
            return '404 Not Found';
        }
        header('Content-Type: image/jpeg', true, 200);
        return file_get_contents($path) ?: '';
    }
}
