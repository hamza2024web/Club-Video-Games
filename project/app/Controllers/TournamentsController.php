<?php
namespace App\Controllers;
use App\Services\MembreServices;
use App\Services\PayementServices;
use App\Services\TournamentsServices;

session_start();

class TournamentsController extends BaseController {
    protected $MembreServices;
    protected $TournamentsServices;
    protected $PaymentServices;

    public function __construct()
    {
        parent::__construct();
        $this->MembreServices = new MembreServices();
        $this->PaymentServices = new PayementServices();
        $this->TournamentsServices = new TournamentsServices();
    }

    public function index(){
        $user_id = $_SESSION["user_id"];
        $member = $this->MembreServices->getProfileMembre($user_id);
        $my_solde = $this->solde($user_id);
        $tournois = $this->TournamentsServices->getAllTournoi();
        $inscriptions = $this->TournamentsServices->getInscriptionTournoi($user_id);
        $calendries = $this->TournamentsServices->getCalendries($user_id);
        return $this->renderMem('tournaments',compact('member','my_solde','tournois','inscriptions','calendries'));
    }
    
    public function inscriptionTournoi(){
        $user_id = $_SESSION["user_id"];
        $tournoi_id = $_POST["tournoi_id"];
        $frais_inscription = $_POST["frais_inscription"];
        $currentSoldeData = $this->PaymentServices->validatePrice($user_id);

        $inscription = $this->TournamentsServices->Inscription($user_id,$tournoi_id,$frais_inscription, $currentSoldeData);

        if($inscription){
            header("location: /member/tournaments?Inscription_succefully=1");
        } else {
            header("location: /member/tournaments?inscription_error=1");
        }
    }
}
?>