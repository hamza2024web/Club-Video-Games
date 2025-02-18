<?php

namespace App\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class BaseController
{
    protected $twigPro;
    protected $twigAdmin;
    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../Views');
        $this->twigPro = new Environment($loader, [
            'cache' => false,
            'debug' => true,
        ]);
    }

    protected function render($template, $data = [])
    {
        echo $this->twigPro->render($template . '.twig', $data);
    }
}