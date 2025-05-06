<?php
namespace App\Models;

class Jeu {
    private $title;
    private $description;
    private $plateforme;
    private $date_de_sortie;
    private $developpeur;
    private $image;
    private $prix;
    private $status;

    public function __construct($title,$description,$plateforme,$date_de_sortie,$developpeur,$image,$prix,$status)
    {
        $this->title = $title;
        $this->description = $description;
        $this->plateforme = $plateforme;
        $this->date_de_sortie = $date_de_sortie;
        $this->developpeur = $developpeur;
        $this->image = $image;
        $this->prix = $prix;
        $this->status = $status;
    }

    public function getTitle() {
        return $this->title;
    }
    
    public function getDescription() {
        return $this->description;
    }
    
    public function getPlateforme() {
        return $this->plateforme;
    }
    
    public function getDateDeSortie() {
        return $this->date_de_sortie;
    }
    
    public function getDeveloppeur() {
        return $this->developpeur;
    }
    
    public function getImage() {
        return $this->image;
    }
    
    public function getPrix() {
        return $this->prix;
    }
    
    public function getStatus() {
        return $this->status;
    }
    
}
?>