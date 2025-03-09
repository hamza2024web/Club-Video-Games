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
        return $this->renderOrg('profile');
    }
    public function updateProfile(){
        $user_id = $_SESSION["user_id"];
        $name = $_POST["full_name"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $gamer_tag = $_POST["gamer_tag"];
        $bio = $_POST["bio"];
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $upload_dir = '../../public/uploads';
            $file_name = basename($_FILES['profile_image']['name']);
            $target_path = $upload_dir . $file_name;
    
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_path)) {
                $profile_image = "../../public/uploads" . $file_name;
                $profile = $this->ProfileServices->saveProfile($user_id,$name,$email,$phone,$gamer_tag,$profile_image,$bio);
            }
        }


        if ($profile) {
            echo "Profile updated successfully!";
        } else {
            echo "Failed to update profile.";
        }
    }
}

?>