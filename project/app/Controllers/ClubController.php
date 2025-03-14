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
    public function updateClub(){
        $user_id = $_SESSION["user_id"];

        $currentClub = $this->clubServices->getClubUser($user_id);

        $name = $_POST["club_name"] ?? $currentClub["name"];
        $email = $_POST["email"] ?? $currentClub["email"];
        $phone_club = $_POST["phone_club"] ?? $currentClub["date_de_creation"];
        $description = $_POST["description"] ?? $currentClub["description"];
        $logo = $currentClub["logo"] ?? 'default.jpg';
        $logo = $_FILES["logo"];

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            $upload_dir = __DIR__ . '/../../public/uploads/';
            $file_name = time() . '_' . basename($_FILES['logo']['name']);
            $target_path = $upload_dir . $file_name;

            $file_type = mime_content_type($_FILES['logo']['tmp_name']);
            if (strpos($file_type, 'image') === false) {
                die("Invalid file type. Please upload an image.");
            }
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_path)) {
                $logo = 'public/uploads/' . $file_name; 
            } else {
                die("Failed to upload image.");
            }
        }
        $saveClub = $this->clubServices->saveClubInf($user_id,$name,$email,$phone_club,$description,$logo);
        if ($saveClub) {
            header("location: /ClubManagement?club_updated_successffly=1");
        } else {
            echo "Failed to update club.";
        }
    }
        
}
?>