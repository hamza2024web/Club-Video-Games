<?php
namespace App\Repository;

use Config\Database;
use PDO;

class EventsMemberRepository {
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
     
    public function getEvents(){
        $sql = "SELECT evenement.id , evenement.name , evenement.date_debut , evenement.date_fin , evenement.lieu , evenement.statut ,evenement.description,evenement.type_evenement,evenement.numbre_membre,evenement.registration_start,evenement.registration_end,evenement.event_time,evenement.event_photo,evenement.entry_fee,evenement.requirements,evenement.discord_url,evenement.twitch_url,GROUP_CONCAT(evenement_programme.timeline_time),GROUP_CONCAT(evenement_programme.timeline_title),GROUP_CONCAT(evenement_programme.timeline_desc) , COUNT(inscription_evenement.membre_id) AS nombre_participants from evenement 
        INNER JOIN evenement_programme ON evenement_programme.event_id = evenement.id
        LEFT JOIN inscription_evenement ON inscription_evenement.evenement_id = evenement.id
        WHERE NOT evenement.statut = 'cancelled'
        GROUP BY evenement.id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $events;
    }
}
?>