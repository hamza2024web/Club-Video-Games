<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\CompteServices;
session_start();

class CompteController extends BaseController {
    protected $CompteServices; 
    public function __construct()
    {
        parent::__construct();
        $this->CompteServices = new CompteServices();
    }

    public function rechargeCompte() {
        
        $user_id = $_SESSION["user_id"];
        $montant = (float)$_POST["solde"]; 
        
        
        // Effectuer le rechargement
        $success = $this->CompteServices->setSolde($user_id, $montant);
        
        // Obtenir la page de référence ou utiliser tournoi par défaut
        $referer = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) : '/tournoi';
        
        if ($success) {
            header("location: " . $referer . "?Rechargement_de_votre_solde_réussite=1");
        } else {
            header("location: " . $referer . "?Rechargement_de_votre_solde_échouée=1");
        }
        exit;
    }

}
?>