<?php
namespace App\Repository;

use Config\Database;
use Exception;
use PDO;
use PDOException;

class EvenementRepository {
    private $conn;
    private function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    public function addEvent($user_id,$name_event,$registration_start,$registration_end,$type_event,$status,$location,$event_date,$event_time,$event_photo,$max_participants,$entry_fee,$description,$requirements,$timeline_time,$timeline_title,$timeline_desc,$discord_url,$twitch_url){
        // Récupérer le club_id de l'organisateur
        $query = "SELECT club_id FROM organisateur WHERE user_id=:user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        $club_idd = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$club_idd) {
            return false; // L'organisateur n'existe pas ou n'a pas de club
        }
        
        $club_id = $club_idd["club_id"];
        
        try {
            $this->conn->beginTransaction();

            // Insérer dans la table événement
            $sqlEvent = "INSERT INTO evenement (name, date_debut, event_time, description, type_evenement, statut, numbre_membre, club_id,registration_start,registration_end,event_time,event_photo,entry_fee,requirements,discord_url,twitch_url)             VALUES (:name, :event_date, :description, :type_evenement, :statut, :numbre_membre, :club_id,:registration_start,:registration_end,:event_time,:event_photo,:entry_fee,:requirements,:discord_url,:twitch_ur)";
            $stmt = $this->conn->prepare($sqlEvent);
            $stmt->bindParam(":name", $name_event);
            $stmt->bindParam(":date_debut", $event_date);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":type_evenement", $type_event);
            $stmt->bindParam(":statut", $status);
            $stmt->bindParam(":numbre_membre", $max_participants);
            $stmt->bindParam(":lieu",$location);
            $stmt->bindParam(":club_id", $club_id);
            $stmt->bindParam(":registration_start",$registration_start);
            $stmt->bindParam(":registration_end",$registration_end);
            $stmt->bindParam(":event_time",$event_time);
            $stmt->bindParam(":event_photo",$event_photo);
            $stmt->bindParam(":entry_fee",$entry_fee);
            $stmt->bindParam(":requirements",$requirements);
            $stmt->bindParam(":discord_url",$discord_url);
            $stmt->bindParam(":twitch_url",$twitch_url);
            $isEventInserted = $stmt->execute();
            $event_id = $this->conn->lastInsertId();
            if (!$isEventInserted || !$event_id) {
                throw new Exception("Failed to insert event. event ID is null.");
            }
            $attachToProgramme = $this->attachEventToProgramme($event_id,$timeline_time,$timeline_title,$timeline_desc);
            if (!$attachToProgramme) {
                throw new Exception("Failed to attach genres.");
            }
            $this->conn->commit();
            return $event_id;
        } catch (PDOException $e){
            $this->conn->rollBack();
            echo "Error adding event: " . $e->getMessage();
            return null;
        }
    }
    private function attachEventToProgramme($event_id,$timeline_time,$timeline_title,$timeline_desc){
        try {
            $sqlProgramme = "INSERT INTO evenement_programme (timeline_time	,timeline_title,timeline_desc,event_id) VALUES (:timeline_time,:timeline_title,:timeline_desc,:event_id)";
            $stmt = $this->conn->prepare($sqlProgramme);
            foreach ($event_id as $event){
                $stmt->bindParam(":timeline_time",$timeline_time);
                $stmt->bindParam(":timeline_title",$timeline_title);
                $stmt->bindParam(":timeline_desc",$timeline_desc);
                $stmt->bindParam(":event_id",$event);
            }
            return $event_id;
        } catch (PDOException $e){
            echo "Error attaching event to programme:" . $e->getMessage();
            return null;
        }
    }
}
?>