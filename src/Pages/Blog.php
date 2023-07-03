<?php

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\Database;
use Parsedown;
use Twig\Environment;
use Twig\TwigFilter;

class Blog
{
    public static function get(Environment $twig, string $lang): string
    {
        $database = Database::get();
        $posts = $database->query('SELECT * FROM post WHERE created < NOW() ORDER BY created DESC')->fetchAll();
        switch ($lang) {
            case 'en':
                return $twig->render('blog.twig', [
                    'title' => 'Blog',
                    'active' => '/blog',
                    'description' => 'Björn Büttner\'s blogposts about web development',
                    'posts' => $posts,
                    'content' => [
                        'title' => 'Newest blogposts',
                        'description' => 'Here are all blog posts, with the newest one at top. I hope you enjoy reading them and learn something from them.',
                    ],
                ]);
            case 'de':
            default:
                return $twig->render('blog.twig', [
                    'title' => 'Blog',
                    'active' => '/blog',
                    'description' => 'Björn Büttners Blog über Webentwicklung',
                    'posts' => $posts,
                    'content' => [
                        'title' => 'Neuste Blogbeiträge',
                        'description' => 'Hier sind alle Blogbeiträge, mit dem Neusten zuerst. Ich hoffe, Sie haben Spaß beim Lesen und lernen etwas dabei.'
                    ],
                ]);
        }
    }
    public static function sitemap(Environment $twig, string $lang): string
    {
        $database = Database::get();
        $posts = $database->query('SELECT slug,created FROM post WHERE created < NOW() ORDER BY created DESC')->fetchAll();
        header('Content-Type: application/xml');
        return $twig->render('sitemap.twig', [
            'slugs' => $posts,
        ]);
    }
    public static function rss(Environment $twig, string $lang): string
    {
        $database = Database::get();
        $posts = $database->query('SELECT * FROM post WHERE created < NOW() ORDER BY created DESC LIMIT 5')->fetchAll();
        header('Content-Type: application/rss+xml');
        return $twig->render('rss.twig', [
            'posts' => $posts,
        ]);
    }
    public static function detail(Environment $twig, string $lang, array $args): string
    {
        $database = Database::get();
        $stmt = $database->prepare('SELECT * FROM post WHERE slug=:slug AND created < NOW() LIMIT 1');
        $stmt->execute(['slug' => $args['slug']]);
        $post = $stmt->fetch();
        if (!$post) {
            header('', true, 404);
            return "404 NOT FOUND";
        }
        $parsedown = new Parsedown();
        $twig->addFilter(new TwigFilter('markdown', function ($markdown) use ($parsedown) {
            return $parsedown->text($markdown);
        }, ['is_safe' => ['html']]));
        switch ($lang) {
            case 'en':
                return $twig->render('blogpost.twig', [
                    'title' => $post['title_en'],
                    'active' => '/blog/' . $args['slug'],
                    'description' => $post['extract_en'],
                    'content' => $post['content_en'],
                ]);
            case 'de':
            default:
                return $twig->render('blogpost.twig', [
                    'title' => $post['title_de'],
                    'active' => '/blog/' . $args['slug'],
                    'description' => $post['extract_de'],
                    'content' => $post['content_de'],
                ]);
        }
    }
}