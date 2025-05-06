<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\dashboardServices;
use App\Services\MembreServices;
use App\Services\PayementServices;  
session_start();    

class BoutiqueMember extends BaseController{
    protected $AdminServices ;
    protected $PayementServices;
    protected $MembreServices;
    public function __construct()
    {
        parent::__construct();
        $this->AdminServices = new dashboardServices();
        $this->PayementServices = new PayementServices();
        $this->MembreServices = new MembreServices();
    }

    public function index(){
        $user_id = $_SESSION["user_id"];
        $my_solde = $this->solde($user_id);
        $genres = $this->AdminServices->getGenre();
        $profile = $this->MembreServices->getProfileMembre($user_id);
        $jeux = $this->AdminServices->getGame();
        $jeuxAchetes = $this->PayementServices->getUserPurchasedGames($user_id);
        $jeuxAchetesIds = [];
        foreach ($jeuxAchetes as $jeu) {
            $jeuxAchetesIds[] = $jeu['jeu_id'];
        }
        return $this->renderMem('boutique', compact('jeux', 'profile', 'genres', 'jeuxAchetesIds','my_solde'));
    }
}
?>