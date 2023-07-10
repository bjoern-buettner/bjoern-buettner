<?php

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\Database;
use Me\BjoernBuettner\HTMLBuilder;
use Twig\Environment;

class Team
{
    public function __construct(private readonly HTMLBuilder $twig)
    {
    }
    public function get(string $lang): string
    {
        $team = Database::get()->query('SELECT * FROM teammember')->fetchAll();
        switch ($lang) {
            case 'en':
                return $this->twig->renderMinified('team.twig', [
                    'title' => 'Team & About Us',
                    'active' => '/team',
                    'description' => 'A little bit about the team behind bjoern-buettner.me',
                    'content' => [
                        'title' => 'About us - the team',
                    ],
                    'team' => $team,
                ], $lang);
            case 'de':
            default:
                return $this->twig->renderMinified('team.twig', [
                    'title' => 'Team & Über Uns',
                    'active' => '/team',
                    'description' => 'Ein wenig über das Team hinter bjoern-buettner.me',
                    'content' => [
                        'title' => 'Über Uns - Das Team',
                    ],
                    'team' => $team,
                ], $lang);
        }
    }
    public function image(string $slug): string
    {
        if (!preg_match('/^[a-z0-9-]+$/', $slug)) {
            header('HTTP/1.1 404 Not Found', true, 404)   ;
            return '404 Not Found';
        }
        $path = __DIR__ . '/../../resources/team/' . $slug . '.jpg';
        header('Content-Type: image/jpeg', true, 200);
        if (!file_exists($path)) {
            return file_get_contents(__DIR__ . '/../../resources/team/placeholder.jpg');
        }
        return file_get_contents($path) ?: '';
    }
}
