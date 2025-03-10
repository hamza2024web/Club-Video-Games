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
    public function updateProfile() {
        $user_id = $_SESSION["user_id"];
        $name = $_POST["full_name"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $gamer_tag = $_POST["gamer_tag"];
        $bio = $_POST["bio"];
        $profile_image = null;
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
    
}

?>