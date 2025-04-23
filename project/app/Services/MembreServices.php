<?php
namespace App\Services;
use App\Repository\MembreRepository;

class MembreServices {
    protected $Membrerepository;

    public function __construct()
    {
        $this->Membrerepository = new MembreRepository();
    }

    public function getFriends(){
        $friends = $this->Membrerepository->getfriends();
        return $friends;
    }
    public function getProfileMembre($user_id){
        $profile = $this->Membrerepository->getProfile($user_id);
        return $profile;
    }

    public function saveProfileMembre($user_id,$username,$email,$tag_name,$location,$about,$discord,$instagram,$youtube,$twitch,$cover_photo,$profile_photo){
        $newProfile = $this->Membrerepository->updateProfile($user_id,$username,$email,$tag_name,$location,$about,$discord,$instagram,$youtube,$twitch,$cover_photo,$profile_photo);
        return $newProfile;
    }
    
    public function CountNotifications($user_id){
        $notification = $this->Membrerepository->getAllNotification($user_id);
        return $notification;
    }
    
    public function GetAllNotifications($user_id){
        $notifications = $this->Membrerepository->getNotifications($user_id);
        return $notifications;
    }
}

?>