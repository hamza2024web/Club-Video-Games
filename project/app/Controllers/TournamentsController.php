<?php
namespace App\Controllers;
use App\Services\MembreServices;
use App\Services\TournamentsServices;

session_start();

class TournamentsController extends BaseController {
    protected $MembreServices;
    protected $TournamentsServices;
    public function __construct()
    {
        parent::__construct();
        $this->MembreServices = new MembreServices();
        $this->TournamentsServices = new TournamentsServices();
    }

    public function index(){
        $user_id = $_SESSION["user_id"];
        $member = $this->MembreServices->getProfileMembre($user_id);
        $my_solde = $this->solde($user_id);
        $tournois = $this->TournamentsServices->getAllTournoi();
        return $this->renderMem('tournaments',compact('member','my_solde','tournois'));
    }
}
?>