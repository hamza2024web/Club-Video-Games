<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\JeuxServices;
use App\Services\MembreServices;
use App\Services\StatistiqueAdminServices;

session_start();

class membreController extends BaseController{
    protected $MembreServices;
    protected $StatistiqueAdminServices;
    protected $JeuxServices;
    public function __construct()
    {
        parent::__construct();
        $this->MembreServices = new MembreServices();
        $this->StatistiqueAdminServices = new StatistiqueAdminServices();
        $this->JeuxServices = new JeuxServices();
    }

    public function dashboard (){
        $user_id = $_SESSION["user_id"];
        $member = $this->MembreServices->getProfileMembre($user_id);
        $my_solde = $this->solde($user_id);
        $notification = $this->MembreServices->CountNotifications($user_id);
        $Jeux_disponibles = $this->Jeux_disponibles($user_id);
        $tournoi_inscrit = $this->Tournoi($user_id);
        $myGames = $this->JeuxServices->getGame($user_id);
        $this->renderMem('dashboard',compact('member','my_solde','notification','Jeux_disponibles','tournoi_inscrit','myGames'));
    }
    public function Jeux_disponibles($user_id){
        $jeux = $this->StatistiqueAdminServices->GamesPurchase($user_id);
        return $jeux;
    }

    public function Tournoi($user_id){
        $tournoi = $this->StatistiqueAdminServices->tournoi_inscrit($user_id);
        return $tournoi;
    }
}
?>