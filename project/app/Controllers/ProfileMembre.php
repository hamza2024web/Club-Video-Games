<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\MembreServices;
use App\Controllers\IProfile;
use App\Services\ProfileServices;

session_start();

class ProfileMembre extends BaseController implements IProfile {

    protected $MembreServices;
    protected $ProfileServices;
    public function __construct()
    {
        parent::__construct();
        $this->MembreServices = new MembreServices();
        $this->ProfileServices = new ProfileServices();

    }
    public function profile (){
        $user_id = $_SESSION["user_id"];
        $profile = $this->MembreServices->getProfileMembre($user_id);
        return $this->renderMem('profile',compact('profile'));
    }
    public function updateProfile() {
        $user_id = $_SESSION["user_id"];

        $currentProfile = $this->MembreServices->getProfileMembre($user_id);
        $username = $_POST["username"] ?? $currentProfile["name"];
        $email = $_POST["email"] ?? $currentProfile["email"];
        $tag_name = $_POST["displayName"] ?? $currentProfile["tag_name"];
        $location = $_POST["location"] ?? $currentProfile["location"];
        $about = $_POST["about"] ?? $currentProfile["about_me"];
        $discord = $_POST["discord"] ?? $currentProfile["discord"];
        $instagram = $_POST["instagram"] ?? $currentProfile["instagram"];
        $youtube = $_POST["youtube"] ?? $currentProfile["youtube"];
        $twitch = $_POST["twitch"] ?? $currentProfile["twitch"];
        $coverPhoto = $_FILES["coverPhoto"] ?? $currentProfile["cover_photo"];
        $cover_photo = $this->generateImage($coverPhoto,$currentProfile["cover_photo"]);
        $profilePhoto = $_FILES["profilePhoto"] ?? $currentProfile["profile_photo"];
        $profile_photo = $this->generateImage($profilePhoto,$currentProfile["profile_photo"]);
        
        $profile = $this->MembreServices->saveProfileMembre($user_id,$username,$email,$tag_name,$location,$about,$discord,$instagram,$youtube,$twitch,$cover_photo,$profile_photo);
    
        if ($profile) {
            header("location: /member/profile?profile_updated_successffly=1");
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