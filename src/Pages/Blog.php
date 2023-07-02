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
        $posts = $database->query('SELECT * FROM post ORDER BY created DESC LIMIT 5')->fetchAll();
        switch ($lang) {
            case 'en':
                return $twig->render('blog.twig', [
                    'title' => 'Blog',
                    'active' => '/blog',
                    'description' => 'Björn Büttner\'s blogposts about web development',
                    'posts' => $posts,
                ]);
            case 'de':
            default:
                return $twig->render('blog.twig', [
                    'title' => 'Blog',
                    'active' => '/blog',
                    'description' => 'Björn Büttners Blog über Webentwicklung',
                    'posts' => $posts,
                ]);
        }
    }
    public static function detail(Environment $twig, string $lang, string $slug): string
    {
        $database = Database::get();
        $stmt = $database->query('SELECT * FROM post WHERE slug=:slug AND created < NOW() DESC LIMIT 1');
        $stmt->execute(['slug' => $slug]);
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
                    'active' => '/blog/' . $slug,
                    'description' => $post['extract_en'],
                    'content' => $post['content_en'],
                ]);
            case 'de':
            default:
                return $twig->render('blogpost.twig', [
                    'title' => $post['title_de'],
                    'active' => '/blog/' . $slug,
                    'description' => $post['extract_de'],
                    'content' => $post['content_de'],
                ]);
        }
    }
}