<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\dashboardServices;
use App\Services\JeuxServices;
use App\Services\MembreServices;
session_start();

class JeuxMember extends BaseController {
    protected $JeuxServices;
    protected $MemberServices;
    protected $AdminServices;

    public function __construct()
    {
        parent::__construct();
        $this->JeuxServices = new JeuxServices();
        $this->MemberServices = new MembreServices();
        $this->AdminServices = new dashboardServices();
    }
    public function index(){
        $user_id = $_SESSION["user_id"];
        $my_solde = $this->solde($user_id);
        $profile = $this->MemberServices->getProfileMembre($user_id);
        $jeuxAchetes = $this->JeuxServices->getGame($user_id);
        $genres = $this->AdminServices->getGenre();
        return $this->renderMem('jeux', compact('profile', 'jeuxAchetes', 'genres','my_solde'));
    }
}
?>