<?php
namespace App\Services;
use App\Repository\ClubRepository;

class ClubServices {
    protected $clubRepository;

    public function __construct()
    {
        $this->clubRepository = new ClubRepository();
    }

    public function getClubUser($user_id){
        $club = $this->clubRepository->getClubOrganisateur($user_id);
        return $club;        
    }

    public function saveClubInf($user_id,$name,$email,$phone_club,$description,$logo){
        return $this->clubRepository->updateClub($user_id,$name,$email,$phone_club,$description,$logo);
    }

    
    public function getMembresClub($user_id){
        $memebrs = $this->clubRepository->getMembers($user_id);
        return $memebrs;
    }
}
?>