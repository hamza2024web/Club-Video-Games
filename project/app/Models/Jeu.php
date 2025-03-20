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
}
?>