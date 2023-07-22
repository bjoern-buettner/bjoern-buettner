<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\Database;
use Me\BjoernBuettner\Entity\Keyword;
use Me\BjoernBuettner\Entity\Post;
use Me\BjoernBuettner\Entity\PostKeyword;
use Me\BjoernBuettner\Entity\Teammember;
use Me\BjoernBuettner\TextOutputBuilder;

class Blog
{
    public function __construct(private readonly TextOutputBuilder $twig, private readonly Database $database)
    {
    }
    public function get(string $lang): string
    {
        $posts = $this->database->load(Post::class, ['active' => true]);
        $authors = [];
        $keywords = [];
        foreach ($posts as &$post) {
            if (!isset($authors[$post['author']])) {
                $authors[$post['author']] = $this->database->load(TeamMember::class, ['aid' => $post['author']])[0];
            }
            $post['author'] = $authors[$post['author']];
            $postKeywords = $this->database->load(PostKeyword::class, ['post' => $post['id']]);
            $post['keywords'] = [];
            foreach ($postKeywords as $postKeyword) {
                if (!isset($keywords[$postKeyword['keyword']])) {
                    $keywords[$postKeyword['keyword']] = $this->database->load(
                        Keyword::class,
                        ['id' => $postKeyword['keyword']]
                    )[0];
                }
                $post['keywords'][] = $keywords[$postKeyword['keyword']];
            }
        }
        switch ($lang) {
            case 'en':
                return $this->twig->renderHTML('blog.twig', [
                    'title' => 'Blog Post Overview',
                    'active' => '/blog',
                    'description' => 'Björn Büttner\'s blog about web development',
                    'posts' => $posts,
                    'content' => [
                        'title' => 'Newest blog posts',
                        'costs' => 'Of course all our content is free to access. If you want to support us, just share
the blogpost with your friends and family. Thank you!',
                        'if_missing' => 'If your desired topic is not in the list yet, just send a mail to
blog@bjoern-buettner.me - we will take care of it quickly.',
                        'description' => 'Here is an overview over all blogposts, with the newest one at top.
I hope you enjoy reading them and learn something from them. The topics of the blog are about web development in every
form and vary from week to week.',
                    ],
                    'og_type' => 'blog',
                ], $lang);
            case 'de':
            default:
                return $this->twig->renderHTML('blog.twig', [
                    'title' => 'Blogpostübersicht',
                    'active' => '/blog',
                    'description' => 'Björn Büttners Blog über Webentwicklung',
                    'posts' => $posts,
                    'content' => [
                        'title' => 'Neuste Blogbeiträge',
                        'costs' => 'Selbstverständlich sind alle unsere Inhalte kostenlos erreichbar. 
Wenn Sie uns unterstützen wollen, so teilen Sie den jeweiligen Blogpost doch einfach mit Ihren Freunden und Bekannten.
Vielen Dank!',
                        'if_missing' => 'Falls Ihr Wunschthema noch nicht in der Liste steht,
schicken Sie doch einfach eine Mail an blog@bjoern-buettner.me - wir kümmern uns schnell darum.',
                        'description' => 'Hier ist eine Übersicht über alle Blogbeiträge, mit jeweils dem Neusten
zuerst.Ich hoffe, Sie haben Spaß beim Lesen und lernen etwas dabei. Die Themen des Blogs
drehen sich um Webentwicklung in jeder Form und variieren von Woche zu Woche.',
                    ],
                    'og_type' => 'blog',
                ], $lang);
        }
    }
    public function sitemap(string $lang): string
    {
        $posts = $this->database->load(Post::class, ['active' => '1']);
        header('Content-Type: application/xml');
        return $this->twig->renderXML('sitemap.twig', [
            'slugs' => $posts,
        ], $lang);
    }
    public function rss(string $lang): string
    {
        $posts = $this->database->load(Post::class, ['active' => '1']);
        $authors = [];
        foreach ($posts as &$post) {
            if (!isset($authors[$post['author']])) {
                $authors[$post['author']] = $this->database->load(TeamMember::class, ['aid' => $post['author']])[0];
            }
            $post['author'] = $authors[$post['author']];
            $post['created'] = date('r', strtotime($post['created']));
        }
        $latest = $posts[0]['created'];
        header('Content-Type: application/rss+xml; charset=utf-8');
        return $this->twig->renderXML("rss-$lang.twig", [
            'posts' => $posts,
            'now' => $latest,
        ], $lang);
    }
    public function detail(string $lang, string $slug): string
    {
        $post = $this->database->load(Post::class, ['slug' => $slug, 'active' => true])[0];
        if (!$post) {
            header('', true, 404);
            return "404 NOT FOUND";
        }
        $author = $this->database->prepare(Teammember::class, ['aid' => $post['author']]);
        $postKeywords = $this->database->load(PostKeyword::class, ['post' => $post['id']]);
        $keywords = [];
        foreach ($postKeywords as $postKeyword) {
            $keywords[] = $this->database->load(
                Keyword::class,
                ['id' => $postKeyword['keyword']]
            )[0];
        }
        switch ($lang) {
            case 'en':
                return $this->twig->renderHTML('blogpost.twig', [
                    'title' => $post['title_en'],
                    'active' => '/blog/' . $slug,
                    'author' => $author,
                    'description' => $post['extract_en'],
                    'content' => $post['content_en'],
                    'og_type' => 'article',
                    'keywords' => $keywords,
                ], $lang);
            case 'de':
            default:
                return $this->twig->renderHTML('blogpost.twig', [
                    'title' => $post['title_de'],
                    'active' => '/blog/' . $slug,
                    'author' => $author,
                    'description' => $post['extract_de'],
                    'content' => $post['content_de'],
                    'og_type' => 'article',
                    'keywords' => $keywords,
                ], $lang);
        }
    }
}
