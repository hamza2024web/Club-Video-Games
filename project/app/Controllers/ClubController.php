<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\ClubServices;
use App\Services\MembreServices;
use App\Services\ProfileServices;
use App\Services\StatistiqueAdminServices;
session_start();

class ClubController extends BaseController {
    protected $clubServices;
    protected $profileServices;
    protected $StatistiqueAdminServices;
    protected $MembreServices;

    public function __construct()
    {
        parent::__construct();
        $this->clubServices = new ClubServices();
        $this->profileServices = new ProfileServices();
        $this->StatistiqueAdminServices = new StatistiqueAdminServices();
        $this->MembreServices = new MembreServices();
    }
    public function index() {
        $user_id = $_SESSION["user_id"];
        $my_solde = $this->solde($user_id);
        $profile = $this->profileServices->getProfileUSer($user_id);
        $notifications = $this->MembreServices->CountNotifications($user_id);
        $club = $this->clubServices->getClubUser($user_id);
        $Membres_actifs = $this->membres_actif();
        $evenements = $this->evenements($user_id);
        $Jeux_disponibles = $this->Jeux_disponibles($user_id);
        $members = $this->clubServices->getMembresClub($user_id);
        return $this->renderOrg('ClubManagement', compact('profile', 'club','my_solde','Membres_actifs','evenements','Jeux_disponibles','notifications','members'));
    }

    public function updateClub() {
        $user_id = $_SESSION["user_id"];
        
        $currentClub = $this->clubServices->getClubUser($user_id);
        
        $name = $_POST["club_name"] ?? $currentClub["name"];
        $email = $_POST["email"] ?? $currentClub["email"];
        $phone_club = $_POST["phone_club"] ?? $currentClub["phone_club"];
        $description = $_POST["description"] ?? $currentClub["description"];
        
        $image = $_FILES["logo"] ?? null;
        $currentLogo = $currentClub["logo"] ?? 'public/uploads/default.jpg';
        
        $logo = $this->generateImage($image, $currentLogo);
        
        $saveClub = $this->clubServices->saveClubInf($user_id, $name, $email, $phone_club, $description, $logo);
    
        if ($saveClub) {
            header("Location: /ClubManagement?club_updated_successfully=1");
            exit();
        } else {
            echo "Failed to update club.";
        }
    }
    
    public function membres_actif (){
        $members_active = $this->StatistiqueAdminServices->members_actif();
        return $members_active;
    }

    public function evenements ($user_id){
        $evenements = $this->StatistiqueAdminServices->evenements($user_id);
        return $evenements;
    }

    public function Jeux_disponibles($user_id){
        $jeux = $this->StatistiqueAdminServices->GamesPurchase($user_id);
        return $jeux;
    }
 
}
?>