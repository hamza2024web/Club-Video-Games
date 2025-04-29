<?php

class Club {
    private $name;
    private $email;
    private $photo_club;
    private $logo;
    private $description;
    private $date_de_creation;

    public function __construct($name,$email,$photo_club,$logo,$description,$date_de_creation)
    {
        $this->name = $name;
        $this->email = $email;
        $this->photo_club = $photo_club;
        $this->logo = $logo;
        $this->description = $description;
        $this->date_de_creation = $date_de_creation;
    }

    public function getName(){
        return $this->name;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getPhoto(){
        return $this->photo_club;
    }

    public function getLogo(){
        return $this->logo;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getDate(){
        return $this->date_de_creation;
    }

    public function setName($name){
        return $name;
    }

    public function setEmail($email){
        return $email;
    }

    public function setPhoto($photo_club){
        return $photo_club;
    }

    public function setLogo($logo){
        return $logo;
    }

    public function setDescription($description){
        return $description;
    }

    public function setDate($date_de_creation){
        return $date_de_creation;
    }
}
?>