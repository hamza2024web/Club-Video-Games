<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\CompteServices;

class CompteController extends BaseController {
    protected $CompteServices; 
    public function __construct()
    {
        parent::__construct();
        $this->CompteServices = new CompteServices();
    }
    public function solde(){
        $user_id = $_SESSION["user_id"];
        $solde = $this->CompteServices->getSolde($user_id);
    }

    public function rechargeCompte(){
        $user_id = $_SESSION["user_id"];
        $montant = $_POST["solde"];

        $amount = $this->CompteServices->setSolde($user_id,$montant);
    }

}
?>