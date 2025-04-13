<?php
namespace App\Controllers;

use App\Services\EvenementProgrammeServices;
use App\Services\EventsMemberServices;
use App\Services\MembreServices;
use App\Services\PayementServices;

session_start();

class EvenetsMemberController extends BaseController {
    protected $EvenetsMemberServices;
    protected $MembreServices;
    protected $EvenementProgrammeService;
    protected $PaymentServices;

    public function __construct()
    {
        parent::__construct();
        $this->EvenetsMemberServices = new EventsMemberServices();
        $this->MembreServices = new MembreServices();
        $this->EvenementProgrammeService = new EvenementProgrammeServices();
        $this->PaymentServices = new PayementServices();
    }

    public function index (){
        $user_id = $_SESSION["user_id"];
        $member = $this->MembreServices->getProfileMembre($user_id);
        $my_solde = $this->solde($user_id);
        $events = $this->EvenetsMemberServices->getAllEvents();
        $inscription = $this->EvenetsMemberServices->getInscriptionEvent($user_id);
        foreach ($events as &$event){
            $event["programme"] = $this->EvenementProgrammeService->getByEvenementId($event['id']);
        }
        return $this->renderMem('evenements',compact('member','my_solde','events','inscription'));
    }

    public function inscription(){
        $user_id = $_SESSION["user_id"];
        $event_id = $_POST["event_id"];
        $frais_inscription = $_POST["frais_inscription"];
        $currentSoldeData = $this->PaymentServices->validatePrice($user_id);

        $inscription = $this->EvenetsMemberServices->inscriptionEvent($user_id,$event_id,$frais_inscription,$currentSoldeData);

        if ($inscription){
            header("location: /member/events?Inscription_succefully=1");
        } else {
            header("location: /member/events?inscription_error=1");
        }
    }
}
?>