<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\dashboardServices;
use App\Services\ProfileServices;
use App\Services\PayementServices;  
session_start();    

class BoutiqueMember extends BaseController{
    protected $AdminServices ;
    protected $ProfileServices;
    protected $PayementServices;
    public function __construct()
    {
        parent::__construct();
        $this->AdminServices = new dashboardServices();
        $this->ProfileServices = new ProfileServices();
        $this->PayementServices = new PayementServices();
    }

    public function index(){
        $user_id = $_SESSION["user_id"];
        $my_solde = $this->solde($user_id);
        $genres = $this->AdminServices->getGenre();
        $profile = $this->ProfileServices->getProfileUser($user_id);
        $jeux = $this->AdminServices->getGame();
        $jeuxAchetes = $this->PayementServices->getUserPurchasedGames($user_id);
        $jeuxAchetesIds = [];
        foreach ($jeuxAchetes as $jeu) {
            $jeuxAchetesIds[] = $jeu['jeu_id'];
        }
        return $this->renderOrg('boutiqueMember', compact('jeux', 'profile', 'genres', 'jeuxAchetesIds','my_solde'));
    }
}
?>