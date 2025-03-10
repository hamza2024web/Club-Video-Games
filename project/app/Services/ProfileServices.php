<?php
namespace App\Services;
use App\Repository\ProfileRepository;

class ProfileServices {
    protected $ProfileRepository;

    public function __construct()
    {
        $this->ProfileRepository = new ProfileRepository();
    }
    public function getProfileUSer($user_id){
        $profileData = $this->ProfileRepository->getProfile($user_id);
        return $profileData;
    }
    public function saveProfile($user_id,$name,$email,$phone,$gamer_tag,$profile_image,$bio){
        $this->ProfileRepository->updateUser($user_id, $name, $email);
        return $this->ProfileRepository->updateProfile($user_id, $phone, $gamer_tag, $bio, $profile_image);
    }
}

?>