<?php
namespace App\Controllers;

use App\Services\EvenementProgrammeServices;
use App\Services\EventsMemberServices;
use App\Services\MembreServices;

session_start();

class EvenetsMemberController extends BaseController {
    protected $EvenetsMemberServices;
    protected $MembreServices;
    protected $EvenementProgrammeService;

    public function __construct()
    {
        parent::__construct();
        $this->EvenetsMemberServices = new EventsMemberServices();
        $this->MembreServices = new MembreServices();
        $this->EvenementProgrammeService = new EvenementProgrammeServices();
    }

    public function index (){
        $user_id = $_SESSION["user_id"];
        $member = $this->MembreServices->getProfileMembre($user_id);
        $my_solde = $this->solde($user_id);
        $events = $this->EvenetsMemberServices->getAllEvents();
        foreach ($events as &$event){
            $event["programme"] = $this->EvenementProgrammeService->getByEvenementId($event['id']);
        }
        return $this->renderMem('evenements',compact('member','my_solde','events'));
    }
}
?>