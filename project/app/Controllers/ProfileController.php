<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\ProfileServices;
use App\Controllers\IProfile;
use App\Services\StatistiqueAdminServices;

session_start();

class ProfileController extends BaseController implements IProfile {

    protected $ProfileServices;
    protected $StatistiqueAdminServices;

    public function __construct()
    {
        parent::__construct();
        $this->ProfileServices = new ProfileServices();
        $this->StatistiqueAdminServices = new StatistiqueAdminServices();

    }
    public function profile (){
        $user_id = $_SESSION["user_id"];
        $profile = $this->ProfileServices->getProfileUser($user_id);
        $evenements = $this->evenements($user_id);
        $jeux = $this->Jeux_disponibles($user_id);
        return $this->renderOrg('profile',compact('profile','evenements','jeux'));
    }

    public function evenements ($user_id){
        $evenements = $this->StatistiqueAdminServices->evenements($user_id);
        return $evenements;
    }

    public function Jeux_disponibles($user_id){
        $jeux = $this->StatistiqueAdminServices->GamesPurchase($user_id);
        return $jeux;
    }

    public function updateProfile() {
        $user_id = $_SESSION["user_id"];
    
        $currentProfile = $this->ProfileServices->getProfileUSer($user_id);
    
        $name = $_POST["full_name"] ?? $currentProfile["name"];
        $email = $_POST["email"] ?? $currentProfile["email"];
        $phone = $_POST["phone"] ?? $currentProfile["phone_number"];
        $gamer_tag = $_POST["gamer_tag"] ?? $currentProfile["gamer_tag"];
        $bio = $_POST["bio"] ?? $currentProfile["bio"];
        
        $image = $_FILES["profile_image"] ?? null;
        $currentImage = $currentProfile["profile_image"] ?? 'public/uploads/default.jpg';
    
        $profile_image = $this->generateImage($image, $currentImage);
    
        $profile = $this->ProfileServices->saveProfile($user_id, $name, $email, $phone, $gamer_tag, $profile_image, $bio);
    
        if ($profile) {
            header("location: /profile?profile_updated_successfully=1");
            exit();
        } else {
            echo "Failed to update profile.";
        }
    }
    
    public function UpdatePassword(){
        $user_id = $_SESSION["user_id"];
        $current_password = $_POST["current_password"];
        $new_password = $_POST["new_password"];

        $isPasswordCorrect = $this->ProfileServices->verifyPassword($current_password, $user_id);

        if (!$isPasswordCorrect) {
            header("location: /profile?current_password_incorrect=1");
            exit();
        }
        
        $updatePassword = $this->ProfileServices->updatePassword($user_id, $new_password);
        
        if ($updatePassword) {
            header("location: /profile?password_updated=1");
            exit();
        } else {
            echo "Failed to update password!";
        }
    }
    
}

?>