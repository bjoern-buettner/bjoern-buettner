<?php

namespace Me\BjoernBuettner;

use PDO;
use Twig\Environment;
use Twig\Loader\LoaderInterface;
use voku\helper\HtmlMin;

class TwigWrapper extends Environment
{
    private string $lang;

    public function __construct(LoaderInterface $loader, string $lang)
    {
        parent::__construct($loader);
        $this->lang = $lang;
    }

    public function render($template, array $context = []): string
    {
        $context['loggedIn'] = isset($_SESSION['aid']);
        $context['lang'] = $this->lang;
        $context['menu'] = $this->lang === 'en' ? MenuList::$en : MenuList::$de;
        $context['loggedInUser'] = ['aid' => 0, 'customer' => 0, 'role' => 'Anonymous'];
        if ($context['loggedIn']) {
            $database = Database::get();
            $stmt = $database->prepare('SELECT `aid`,`customer`,`role` FROM `user` WHERE aid=:aid');
            $stmt->execute([':aid' => $_SESSION['aid']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $context['loggedInUser'] = $user;
            }
        }
        $data = parent::render($template, $context);
        $htmlMin = new HtmlMin();
        return $htmlMin->minify($data);
    }
}
