<?php

namespace App\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Exception;

class BaseController
{
    protected $twigPro;
    protected $twigAdmin;
    protected $twigAuth;
    protected $twigOrg;
    protected $twigMem;
    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../Views');
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
        $loaderOrganisateur = new FilesystemLoader(__DIR__ .'/../../Views/organisateur');
        $this->twigOrg = new Environment($loaderOrganisateur, [
            'cache' => false,
            'debug' => true,
        ]);
        $loaderMembre = new FilesystemLoader(__DIR__ . '/../../Views/membre');
        $this->twigMem = new Environment($loaderMembre, [
            'cache' => false,
            'debug' => true,
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
    protected function renderOrg($template ,$data = []){
        echo $this->twigOrg->render($template . '.twig', $data);
    }
    protected function renderMem($template ,$data = []){
        echo $this->twigMem->render($template . '.twig', $data);
    }

    public function generateImage($image, $currentImage) {
        if (!isset($image) || !is_array($image) || !isset($image['error'])) {
            return $currentImage; 
        }
    
        if ($image['error'] !== 0) {
            return $currentImage; 
        }
    
        $upload_dir = __DIR__ . '/../../public/uploads/';
        $file_name = time() . '_' . basename($image['name']);
        $target_path = $upload_dir . $file_name;
    
        $file_type = mime_content_type($image['tmp_name']);
        if (strpos($file_type, 'image') === false) {
            return $currentImage; 
        }
    
        if (move_uploaded_file($image['tmp_name'], $target_path)) {
            return 'public/uploads/' . $file_name;
        }
    
        return $currentImage; 
    }
    
    
}
