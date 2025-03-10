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
    public function updatePassword($user_id,$current_password,$new_password){
        $profilePassword = $this->ProfileRepository->updatePassword($user_id,$current_password,$new_password);
        return $profilePassword;
    }
    // public function verifyPassword($current_password){
    //     $user_password = $this->ProfileRepository->getPassword();
    //     if(password_verify($current_password,)){

    //     }
    // }
}

?>