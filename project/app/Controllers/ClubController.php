<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\ClubServices;
use App\Services\ProfileServices;
use App\Services\StatistiqueAdminServices;
session_start();

class ClubController extends BaseController {
    protected $clubServices;
    protected $profileServices;
    protected $StatistiqueAdminServices;

    public function __construct()
    {
        parent::__construct();
        $this->clubServices = new ClubServices();
        $this->profileServices = new ProfileServices();
        $this->StatistiqueAdminServices = new StatistiqueAdminServices();
    }
    public function index() {
        $user_id = $_SESSION["user_id"];
        $my_solde = $this->solde($user_id);
        $profile = $this->profileServices->getProfileUSer($user_id);
        $club = $this->clubServices->getClubUser($user_id);
        $Membres_actifs = $this->membres_actif();
        $evenements = $this->evenements();
        return $this->renderOrg('ClubManagement', compact('profile', 'club','my_solde'));
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

    public function evenements (){
        $evenements = $this->StatistiqueAdminServices->evenements();
        return $evenements;
    }

    // public function Jeux_disponibles(){
    //     $jeux = $this->StatistiqueAdminServices->GamesPurchase();
    //     return $jeux;
    // }
 
}
?>