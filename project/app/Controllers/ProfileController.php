<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\ProfileServices;

session_start();

class ProfileController extends BaseController{

    protected $ProfileServices;
    public function __construct()
    {
        parent::__construct();
        $this->ProfileServices = new ProfileServices();

    }
    public function profile (){
        $user_id = $_SESSION["user_id"];
        $profile = $this->ProfileServices->getProfileUser($user_id);
        return $this->renderOrg('profile',compact('profile'));
    }
    public function updateProfile() {
        $user_id = $_SESSION["user_id"];

        $currentProfile = $this->ProfileServices->getProfileUSer($user_id);

        $name = $_POST["full_name"] ?? $currentProfile["name"];
        $email = $_POST["email"] ?? $currentProfile["email"];
        $phone = $_POST["phone"] ?? $currentProfile["phone_number"];
        $gamer_tag = $_POST["gamer_tag"] ?? $currentProfile["gamer_tag"];
        $bio = $_POST["bio"] ?? $currentProfile["bio"];
        $profile_image = $currentProfile["profile_image"];

        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $upload_dir = __DIR__ . '/../../public/uploads/';
            $file_name = time() . '_' . basename($_FILES['profile_image']['name']);
            $target_path = $upload_dir . $file_name;

            $file_type = mime_content_type($_FILES['profile_image']['tmp_name']);
            if (strpos($file_type, 'image') === false) {
                die("Invalid file type. Please upload an image.");
            }
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_path)) {
                $profile_image = 'public/uploads/' . $file_name; 
            } else {
                die("Failed to upload image.");
            }
        }
            $profile = $this->ProfileServices->saveProfile($user_id, $name, $email, $phone, $gamer_tag, $profile_image, $bio);
    
        if ($profile) {
            header("location: profile");
        } else {
            echo "Failed to update profile.";
        }
    }
    // public function fetchTheOldPassword($current_password){
    //     $correctPassword = $this->ProfileServices->verifyPassword($current_password);
    //     return $correctPassword;
    // }
    // public function updatePassword(){
    //     $user_id = $_SESSION["user_id"];
    //     $current_password = $_POST["current_password"];
    //     $new_password = $_POST["new_password"];
    //     $correctPassword = $this->fetchTheOldPassword($current_password);
    //     $updatePassword = $this->ProfileServices->updatePassword($user_id,$current_password,$new_password);
    //     if ($updatePassword){
    //         header("location: /profile");
    //     } else {
    //         echo "Failed To Update Password !!";
    //     }
    // }
    
}

?>