<?php
namespace App\Models;

class Evenement {
    private $id;
    private $name;
    private $date_debut;
    private $date_fin;
    private $lieu;
    private $statut;
    private $description;
    private $type_evenement;
    private $nombre_membre;
    private $club_id;
    private $registration_start;
    private $registration_end;
    private $event_time;
    private $event_photo;
    private $entry_fee;
    private $requirements;
    private $discord_url;
    private $twitch_url;

    public function __construct(
        $id, $name, $date_debut, $date_fin, $lieu, $statut, $description,
        $type_evenement, $nombre_membre, $club_id, $registration_start,
        $registration_end, $event_time, $event_photo, $entry_fee,
        $requirements, $discord_url, $twitch_url
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        $this->lieu = $lieu;
        $this->statut = $statut;
        $this->description = $description;
        $this->type_evenement = $type_evenement;
        $this->nombre_membre = $nombre_membre;
        $this->club_id = $club_id;
        $this->registration_start = $registration_start;
        $this->registration_end = $registration_end;
        $this->event_time = $event_time;
        $this->event_photo = $event_photo;
        $this->entry_fee = $entry_fee;
        $this->requirements = $requirements;
        $this->discord_url = $discord_url;
        $this->twitch_url = $twitch_url;
    }

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getDateDebut() { return $this->date_debut; }
    public function getDateFin() { return $this->date_fin; }
    public function getLieu() { return $this->lieu; }
    public function getStatut() { return $this->statut; }
    public function getDescription() { return $this->description; }
    public function getTypeEvenement() { return $this->type_evenement; }
    public function getNombreMembre() { return $this->nombre_membre; }
    public function getClubId() { return $this->club_id; }
    public function getRegistrationStart() { return $this->registration_start; }
    public function getRegistrationEnd() { return $this->registration_end; }
    public function getEventTime() { return $this->event_time; }
    public function getEventPhoto() { return $this->event_photo; }
    public function getEntryFee() { return $this->entry_fee; }
    public function getRequirements() { return $this->requirements; }
    public function getDiscordUrl() { return $this->discord_url; }
    public function getTwitchUrl() { return $this->twitch_url; }

}
?>
