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
    public function updateProfile(){
        $user_id = $_SESSION["user_id"];
        $name = $_POST["full_name"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $gamer_tag = $_POST["gamer_tag"];
        $profile_image = $_POST["profile_image"];
        $bio = $_POST["bio"];
        
        $profile = $this->ProfileServices->saveProfile($user_id,$name,$email,$phone,$gamer_tag,$bio);

    }
}

?>