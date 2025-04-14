<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\EvenementServices;
use App\Services\ProfileServices;
use App\Services\EvenementProgrammeServices;

session_start();

class EvenementController extends BaseController{
    protected $EvenementServices;
    protected $ProfileServices;
    protected $EvenementProgrammeService;
    
    public function __construct()
    {
        parent::__construct();
        $this->ProfileServices = new ProfileServices();
        $this->EvenementServices = new EvenementServices();
        $this->EvenementProgrammeService = new EvenementProgrammeServices();
    }
    public function index(){
        $user_id = $_SESSION["user_id"];
        $my_solde = $this->solde($user_id);
        $profile = $this->ProfileServices->getProfileUser($user_id);
        $events = $this->EvenementServices->getMyEvents($user_id);
        foreach ($events as &$event){
            $event['programme'] = $this->EvenementProgrammeService->getByEvenementId($event['id']);
        }
        return $this->renderOrg('evenement',compact('profile','my_solde','events'));
    }
    public function addEvenement(){
        $user_id = $_SESSION["user_id"];
        $name_event = $_POST["name"];
        $registration_start = $_POST["registration_start"];
        $registration_end = $_POST["registration_end"];
        $type_event = $_POST["category"];
        $location = $_POST["location"];
        $event_date = $_POST["event_date"];
        $event_time = $_POST["event_time"];
        $max_participants = $_POST["max_participants"];
        $entry_fee = $_POST["entry_fee"];
        $description = $_POST["description"];
        $requirements = $_POST["requirements"];
        $timeline_time = $_POST["timeline_time"];
        $timeline_title = $_POST["timeline_title"];
        $timeline_desc = $_POST["timeline_desc"];
        $discord_url = $_POST["discord_url"];
        $twitch_url = $_POST["twitch_url"];
        $currentImage = 'public/uploads/default.jpg';
        $image = $_FILES["event_photo"] ?? null; 

        $event_photo = $this->generateImage($image,$currentImage);
        $currentDate = date('Y-m-d');

        // Logique corrigée pour définir le statut
        if ($currentDate < $registration_start) {
            $status = 'Pending';
        } elseif ($currentDate <= $registration_end) {
            $status = 'Open';
        } else {
            $status = 'Cancelled'; 
        }

        $saveEvent = $this->EvenementServices->setEvent($user_id,$name_event,$registration_start,$registration_end,$type_event,$status,$location,$event_date,$event_time,$event_photo,$max_participants,$entry_fee,$description,$requirements,$timeline_time,$timeline_title,$timeline_desc,$discord_url,$twitch_url);

        if($saveEvent){
            header("location: /organisateur/evenement?evenement Created successfully=1");
        } else {
            header("location: organisateur/evenement?Creation de votre evenement echouée=1");
        }
    }

    public function cancelEvent(){
        $user_id = $_SESSION["user_id"];
        $event_id = $_POST["event_id"];

        $cancelEvent = $this->EvenementServices->CancelAnEvent($user_id,$event_id);

        if($cancelEvent === true){ 
            header("location: /organisateur/evenement?Evenement_Canceled_Succefully=1");
        } else {
            header("location: /organisateur/evenement?Evenement_Canceled_failed=1");
        }
    }

}
?>