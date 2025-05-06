<?php
namespace App\Models;

class Tournoi {
    private $name;
    private $date_debut;
    private $date_fin;
    private $nombre_membre;
    private $regles;
    private $statut;
    private $jeu_id;
    private $event_id;
    private $format_tournoi;
    private $description;
    private $prix_total;
    private $premier_place;
    private $deuxieme_place;
    private $troisieme_place;
    private $date_ouverture_inscription;
    private $date_cloture_inscription;
    private $frais_inscription;
    private $discord;
    private $twitch;
    private $image;

    public function __construct($name, $date_debut, $date_fin, $nombre_membre, $regles, $statut, $jeu_id, $event_id, $format_tournoi, $description, $prix_total, $premier_place, $deuxieme_place, $troisieme_place, $date_ouverture_inscription, $date_cloture_inscription, $frais_inscription, $discord, $twitch, $image) {
        $this->name = $name;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        $this->nombre_membre = $nombre_membre;
        $this->regles = $regles;
        $this->statut = $statut;
        $this->jeu_id = $jeu_id;
        $this->event_id = $event_id;
        $this->format_tournoi = $format_tournoi;
        $this->description = $description;
        $this->prix_total = $prix_total;
        $this->premier_place = $premier_place;
        $this->deuxieme_place = $deuxieme_place;
        $this->troisieme_place = $troisieme_place;
        $this->date_ouverture_inscription = $date_ouverture_inscription;
        $this->date_cloture_inscription = $date_cloture_inscription;
        $this->frais_inscription = $frais_inscription;
        $this->discord = $discord;
        $this->twitch = $twitch;
        $this->image = $image;
    }

    public function getName() {
        return $this->name;
    }

    public function getDateDebut() {
        return $this->date_debut;
    }

    public function getDateFin() {
        return $this->date_fin;
    }

    public function getNombreMembre() {
        return $this->nombre_membre;
    }

    public function getRegles() {
        return $this->regles;
    }

    public function getStatut() {
        return $this->statut;
    }

    public function getJeuId() {
        return $this->jeu_id;
    }

    public function getEventId() {
        return $this->event_id;
    }

    public function getFormatTournoi() {
        return $this->format_tournoi;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrixTotal() {
        return $this->prix_total;
    }

    public function getPremierPlace() {
        return $this->premier_place;
    }

    public function getDeuxiemePlace() {
        return $this->deuxieme_place;
    }

    public function getTroisiemePlace() {
        return $this->troisieme_place;
    }

    public function getDateOuvertureInscription() {
        return $this->date_ouverture_inscription;
    }

    public function getDateClotureInscription() {
        return $this->date_cloture_inscription;
    }

    public function getFraisInscription() {
        return $this->frais_inscription;
    }

    public function getDiscord() {
        return $this->discord;
    }

    public function getTwitch() {
        return $this->twitch;
    }

    public function getImage() {
        return $this->image;
    }
}