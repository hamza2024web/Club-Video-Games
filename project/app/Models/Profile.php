<?php
namespace App\Models;

class Profile {
    private $phone_number;
    private $gamer_tag;
    private $bio;
    private $profile_image;
    private $community_rating;

    public function __construct($phone_number,$gamer_tag,$bio,$profile_image,$community_rating)
    {
        $this->phone_number = $phone_number;
        $this->gamer_tag = $gamer_tag;
        $this->bio = $bio;
        $this->profile_image = $profile_image;
        $this->community_rating = $community_rating;
    }

    public function getPhoneNumber() {
        return $this->phone_number;
    }
    
    public function getGamerTag() {
        return $this->gamer_tag;
    }
    
    public function getBio() {
        return $this->bio;
    }
    
    public function getProfileImage() {
        return $this->profile_image;
    }
    
    public function getCommunityRating() {
        return $this->community_rating;
    }
    
}
?>