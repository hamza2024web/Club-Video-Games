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
        if (!isset($_SESSION["user_id"])) {
            header("location: /login");
            exit;
        }
        
        if (!isset($_POST["solde"]) || !is_numeric($_POST["solde"]) || $_POST["solde"] <= 0) {
            header("location: /tournoi?Rechargement_de_votre_solde_échouée=1");
            exit;
        }
        
        $user_id = $_SESSION["user_id"];
        $montant = (float)$_POST["solde"]; 
        
        
        // Effectuer le rechargement
        $success = $this->CompteServices->setSolde($user_id, $montant);
        
        if ($success) {
            header("location: /tournoi?Rechargement_de_votre_solde_réussite=1");
        } else {
            header("location: /tournoi?Rechargement_de_votre_solde_échouée=1");
        }
        exit;
    }

}
?>