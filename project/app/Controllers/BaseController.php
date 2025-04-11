<?php

namespace App\Controllers;

use App\Services\CompteServices;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;
use Exception;

class BaseController
{
    protected $twigPro;
    protected $twigAdmin;
    protected $twigAuth;
    protected $twigOrg;
    protected $twigMem;
    protected $compteServices;

    public function __construct()
    {
        $this->twigPro = $this->initTwig(__DIR__ . '/../../Views');
        $this->twigAdmin = $this->initTwig(__DIR__ . '/../../Views/Admin');
        $this->twigAuth = $this->initTwig(__DIR__ . '/../../Views/auth');
        $this->twigOrg = $this->initTwig(__DIR__ . '/../../Views/organisateur');
        $this->twigMem = $this->initTwig(__DIR__ . '/../../Views/membre');
        $this->compteServices = new CompteServices();

    }

    private function initTwig($path)
    {
        $loader = new FilesystemLoader($path);
        $twig = new Environment($loader, [
            'cache' => false,
            'debug' => true, // Enable debugging
        ]);
        $twig->addExtension(new DebugExtension()); // Add DebugExtension to enable dump()
        return $twig;
    }

    protected function render($template, $data = [])
    {
        echo $this->twigPro->render($template . '.twig', $data);
    }

    protected function renderAdmin($template, $data = [])
    {
        echo $this->twigAdmin->render($template . '.twig', $data);
    }

    protected function renderAuth($template, $data = [])
    {
        echo $this->twigAuth->render($template . '.twig', $data);
    }

    protected function renderOrg($template, $data = [])
    {
        echo $this->twigOrg->render($template . '.twig', $data);
    }

    protected function renderMem($template, $data = [])
    {
        echo $this->twigMem->render($template . '.twig', $data);
    }

    public function generateImage($image, $currentImage)
    {
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
    public function solde($user_id = null) {
        if ($user_id === null) {
        if (session_status() == PHP_SESSION_NONE) {
        session_start();
        }

        if (!isset($_SESSION["user_id"])) {
        throw new Exception("Aucun utilisateur connecté");
        }

        $user_id = $_SESSION["user_id"];
        }

        return $this->compteServices->getSolde($user_id);
    }

}
