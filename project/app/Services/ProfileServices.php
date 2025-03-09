<?php
namespace App\Services;
use App\Repository\ProfileRepository;

class ProfileServices {
    protected $ProfileRepository;

    public function __construct()
    {
        $this->ProfileRepository = new ProfileRepository();
    }
    public function saveProfile($user_id,$name,$email,$phone,$gamer_tag,$profile_image,$bio){
        $this->ProfileRepository->updateUser($user_id, $name, $email);
        return $this->ProfileRepository->updateProfile($user_id, $phone, $gamer_tag, $bio, $profile_image);
    }
}

?>