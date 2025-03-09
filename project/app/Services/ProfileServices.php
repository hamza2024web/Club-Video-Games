<?php
namespace App\Services;
use App\Repository\ProfileRepository;

class ProfileServices {
    protected $ProfileRepository;

    public function __construct()
    {
        $this->ProfileRepository = new ProfileRepository();
    }
    public function saveProfile($user_id,$name,$email,$phone,$gamer_tag,$bio){
        $profile = $this->ProfileRepository->fillProfile($user_id,$name,$email,$phone,$gamer_tag,$bio);
    }
}

?>