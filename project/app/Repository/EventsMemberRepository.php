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
        $sql = "SELECT evenement.id , evenement.name , evenement.date_debut , evenement.date_fin , evenement.lieu , evenement.statut ,evenement.description,evenement.type_evenement,evenement.numbre_membre,evenement.registration_start,evenement.registration_end,evenement.event_time,evenement.event_photo,evenement.entry_fee,evenement.requirements,evenement.discord_url,evenement.twitch_url,GROUP_CONCAT(evenement_programme.timeline_time),GROUP_CONCAT(evenement_programme.timeline_title),GROUP_CONCAT(evenement_programme.timeline_desc) , COUNT(DISTINCT(inscription_evenement.membre_id)) AS nombre_participants from evenement 
        INNER JOIN evenement_programme ON evenement_programme.event_id = evenement.id
        LEFT JOIN inscription_evenement ON inscription_evenement.evenement_id = evenement.id
        WHERE NOT evenement.statut = 'cancelled'
        GROUP BY evenement.id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $events;
    }

    public function inscription_event($user_id,$event_id,$price){
        $sqlMember = "SELECT id FROM membre WHERE user_id = :user_id";
        $stmtMember = $this->conn->prepare($sqlMember);
        $stmtMember->bindParam(":user_id", $user_id);
        $stmtMember->execute();
        $member = $stmtMember->fetch(PDO::FETCH_ASSOC);
        if (!$member) {
            return false;
        }
        $member_id = $member['id'];
        $sqlInscription = "INSERT INTO inscription_evenement (evenement_id, membre_id,frais_inscription) VALUES (:event_id, :member_id,:frais_inscription)";
        $stmtInsert = $this->conn->prepare($sqlInscription);
        $stmtInsert->bindParam(":event_id", $event_id);
        $stmtInsert->bindParam(":member_id", $member_id);
        $stmtInsert->bindParam(":frais_inscription",$price);
        return $stmtInsert->execute();
    }

    public function GEtinscription_events($user_id){
        $sql = "SELECT evenement.id , evenement.name , evenement.date_debut , evenement.date_fin , evenement.lieu , evenement.statut ,evenement.description,evenement.type_evenement,evenement.numbre_membre,evenement.registration_start,evenement.registration_end,evenement.event_time,evenement.event_photo,evenement.entry_fee,evenement.requirements,evenement.discord_url,evenement.twitch_url,GROUP_CONCAT(evenement_programme.timeline_time),GROUP_CONCAT(evenement_programme.timeline_title),GROUP_CONCAT(evenement_programme.timeline_desc) , COUNT(inscription_evenement.membre_id) AS nombre_participants , inscription_evenement.evenement_id from evenement 
        INNER JOIN evenement_programme ON evenement_programme.event_id = evenement.id
        INNER JOIN inscription_evenement ON inscription_evenement.evenement_id = evenement.id
        INNER JOIN membre ON membre.id = inscription_evenement.membre_id
        INNER JOIN users ON users.id = membre.user_id
        WHERE users.id = :user_id
        GROUP BY evenement.id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id",$user_id);
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $events;
    }

    public function ReadItNotifications($user_id,$notification_id){
        $sql = "UPDATE notifications SET is_read = :is_read WHERE user_id = :user_id AND notifications.id = :id";
        $stmt = $this->conn->prepare($sql);
        $is_read = 1;
        $stmt->bindParam(":is_read",$is_read);
        $stmt->bindParam(":id",$notification_id);
        $stmt->bindParam("user_id",$user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result && $stmt->rowCount() > 0){
            return true;
        } else {
            return false;
        }
    }
}
?>