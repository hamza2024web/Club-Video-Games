<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\ClubServices;
use App\Services\ProfileServices;
session_start();

class ClubController extends BaseController {
    protected $clubServices;
    protected $profileServices;
    public function __construct()
    {
        parent::__construct();
        $this->clubServices = new ClubServices();
        $this->profileServices = new ProfileServices();
    }
    public function index() {
        $user_id = $_SESSION["user_id"];
        $profile = $this->profileServices->getProfileUSer($user_id);
        $club = $this->clubServices->getClubUser($user_id);
        
        return $this->renderOrg('ClubManagement', compact('profile', 'club'));
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
    
    
        
}
?>