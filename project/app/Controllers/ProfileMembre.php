<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\ProfileServices;
use App\Controllers\IProfile;

session_start();

class ProfileMembre extends BaseController implements IProfile {

    protected $ProfileServices;
    public function __construct()
    {
        parent::__construct();
        $this->ProfileServices = new ProfileServices();

    }
    public function profile (){
        // $user_id = $_SESSION["user_id"];
        // $profile = $this->ProfileServices->getProfileMembre($user_id);
        return $this->renderMem('profile');
    }
    public function updateProfile() {
        $user_id = $_SESSION["user_id"];

        $currentProfile = $this->ProfileServices->getProfileUSer($user_id);

        $name = $_POST["full_name"] ?? $currentProfile["name"];
        $email = $_POST["email"] ?? $currentProfile["email"];
        $phone = $_POST["phone"] ?? $currentProfile["phone_number"];
        $gamer_tag = $_POST["gamer_tag"] ?? $currentProfile["gamer_tag"];
        $bio = $_POST["bio"] ?? $currentProfile["bio"];
        $currentImage = $currentProfile["profile_image"] ?? 'default.jpg';
        $image = $_FILES['profile_image'];
        $profile_image = $this->generateImage($image,$currentImage);

        $profile = $this->ProfileServices->saveProfile($user_id, $name, $email, $phone, $gamer_tag, $profile_image, $bio);
    
        if ($profile) {
            header("location: /profile?profile_updated_successffly=1");
        } else {
            echo "Failed to update profile.";
        }
    }
    public function updatePassword(){
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