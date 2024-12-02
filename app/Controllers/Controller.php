<?php declare(strict_types=1);

namespace App\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class Controller
{
    protected Environment $twig;

    public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
        $loader = new FilesystemLoader('../app/Views');
        $this->twig = new Environment($loader);
        $this->twig->addGlobal('session', $_SESSION);
    }
}
