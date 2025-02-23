<?php

namespace App\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class BaseController
{
    protected $twigPro;
    protected $twigAdmin;
    protected $twigAuth;
    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../Views/organisateur');
        $this->twigPro = new Environment($loader, [
            'cache' => false,
            'debug' => true,
        ]);
        $loaderAdmin = new FilesystemLoader(__DIR__.'/../../Views/Admin');
        $this->twigAdmin = new Environment($loaderAdmin, [
            'cache' => false,
            'debug' => true,
        ]);
        $loaderAuth = new FilesystemLoader(__DIR__ . '/../../Views/auth');
        $this->twigAuth = new Environment($loaderAuth, [
            'cache' => false,
            'debug' => false,
        ]);
    }

    protected function render($template, $data = [])
    {
        echo $this->twigPro->render($template . '.twig', $data);
    }
    protected function renderAdmin($template, $data = [])
    {
        echo $this->twigAdmin->render($template . '.twig', $data);
    }
    protected function renderAuth($template,$data = [])
    {
        echo $this->twigAuth->render($template . '.twig', $data);
    }
}
