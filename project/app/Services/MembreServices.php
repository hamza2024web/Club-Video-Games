<?php
namespace App\Services;
use App\Repository\MembreRepository;

class MembreServices {
    protected $Membrerepository;

    public function __construct()
    {
        $this->Membrerepository = new MembreRepository();
    }
    public function getProfileMembre($user_id){
        $profile = $this->Membrerepository->getProifle($user_id);
        return $profile;
    }

    public function saveProfileMembre($user_id,$username,$email,$tag_name,$location,$about,$discord,$instagram,$youtube,$twitch,$covre_photo,$profile_photo){
        $newProfile = $this->Membrerepository->updateProfile($user_id,$username,$email,$tag_name,$location,$about,$discord,$instagram,$youtube,$twitch,$covre_photo,$profile_photo);
    }
}

?>