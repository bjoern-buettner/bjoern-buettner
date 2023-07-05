<?php

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\Database;
use Me\BjoernBuettner\TwigWrapper;
use Twig\Environment;

class Blog
{
    public static function get(Environment $twig, string $lang): string
    {
        $database = Database::get();
        $posts = $database->query('SELECT * FROM post WHERE created < NOW() ORDER BY created DESC')->fetchAll();
        $authors = [];
        foreach ($posts as &$post) {
            if (!isset($authors[$post['author']])) {
                $stmt = $database->prepare('SELECT * FROM teammember WHERE aid=:aid LIMIT 1');
                $stmt->execute(['aid' => $post['author']]);
                $authors[$post['author']] = $stmt->fetch();
            }
            $post['author'] = $authors[$post['author']];
            $stmt = $database->prepare('SELECT keywords.* FROM post_keyword INNER JOIN keyword ON keyword.id=post_keyword.keyword WHERE post_keyword.post=:aid LIMIT 1');
            $stmt->execute(['aid' => $post['id']]);
            $post['keywords'] = $stmt->fetchAll();
        }
        switch ($lang) {
            case 'en':
                return $twig->render('blog.twig', [
                    'title' => 'Blog Post Overview',
                    'active' => '/blog',
                    'description' => 'Björn Büttner\'s blog about web development',
                    'posts' => $posts,
                    'content' => [
                        'title' => 'Newest blog posts',
                        'costs' => 'Of course all our content is free to access. If you want to support us, just share the blogpost with your friends and family. Thank you!',
                        'if_missing' => 'If your desired topic is not in the list yet, just send a mail to blog@bjoern-buettner.me - we will take care of it quickly.',
                        'description' => 'Here is an overview over all blogposts, with the newest one at top. I hope you enjoy reading them and learn something from them. The topics of the blog are about web development in every form and vary from week to week.',
                    ],
                    'og_type' => 'blog',
                ]);
            case 'de':
            default:
                return $twig->render('blog.twig', [
                    'title' => 'Blogpostübersicht',
                    'active' => '/blog',
                    'description' => 'Björn Büttners Blog über Webentwicklung',
                    'posts' => $posts,
                    'content' => [
                        'title' => 'Neuste Blogbeiträge',
                        'costs' => 'Selbstverständlich sind alle unsere Inhalte kostenlos erreichbar. Wenn Sie uns unterstützen wollen, so teilen Sie den jeweiligen Blogpost doch einfach mit Ihren Freunden und Bekannten. Vielen Dank!',
                        'if_missing' => 'Falls Ihr Wunschthema noch nicht in der Liste steht, schicken Sie doch einfach eine Mail an blog@bjoern-buettner.me - wir kümmern uns schnell darum.',
                        'description' => 'Hier ist eine Übersicht über alle Blogbeiträge, mit jeweils dem Neusten zuerst. Ich hoffe, Sie haben Spaß beim Lesen und lernen etwas dabei. Die Themen des Blogs drehen sich um Webentwicklung in jeder Form und variieren von Woche zu Woche.',
                    ],
                    'og_type' => 'blog',
                ]);
        }
    }
    public static function sitemap(TwigWrapper $twig, string $lang): string
    {
        $database = Database::get();
        $posts = $database->query('SELECT slug,created FROM post WHERE created < NOW() ORDER BY created DESC')->fetchAll();
        header('Content-Type: application/xml');
        return $twig->renderUnminified('sitemap.twig', [
            'slugs' => $posts,
        ]);
    }
    public static function rss(TwigWrapper $twig, string $lang): string
    {
        $database = Database::get();
        $posts = $database->query('SELECT * FROM post WHERE created < NOW() ORDER BY created DESC LIMIT 5')->fetchAll();
        $authors = [];
        foreach ($posts as &$post) {
            if (!isset($authors[$post['author']])) {
                $stmt = $database->prepare('SELECT * FROM teammember WHERE aid=:aid LIMIT 1');
                $stmt->execute(['aid' => $post['author']]);
                $authors[$post['author']] = $stmt->fetch();
            }
            $post['author'] = $authors[$post['author']];
        }
        header('Content-Type: application/rss+xml; charset=utf-8');
        return $twig->renderUnminified('rss.twig', [
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
        $stmt = $database->prepare('SELECT * FROM teammember WHERE aid=:aid LIMIT 1');
        $stmt->execute(['aid' => $post['author']]);
        $author = $stmt->fetch();
        $stmt = $database->prepare('SELECT keyword.* FROM post_keyword INNER JOIN keyword ON keyword.id=post_keyword.keyword WHERE post_keyword.post=:aid LIMIT 1');
        $stmt->execute(['aid' => $post['id']]);
        $post['keywords'] = $stmt->fetchAll();
        switch ($lang) {
            case 'en':
                return $twig->render('blogpost.twig', [
                    'title' => $post['title_en'],
                    'active' => '/blog/' . $args['slug'],
                    'author' => $author,
                    'description' => $post['extract_en'],
                    'content' => $post['content_en'],
                    'og_type' => 'article',
                ]);
            case 'de':
            default:
                return $twig->render('blogpost.twig', [
                    'title' => $post['title_de'],
                    'active' => '/blog/' . $args['slug'],
                    'author' => $author,
                    'description' => $post['extract_de'],
                    'content' => $post['content_de'],
                    'og_type' => 'article',
                ]);
        }
    }
}