<?php
namespace App\Repository;

use Config\Database;
use Exception;
use PDO;
use PDOException;

class EvenementRepository {
    private $conn;
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    public function addEvent($user_id, $name_event, $registration_start, $registration_end, $type_event, $status, $location, $event_date, $event_time, $event_photo, $max_participants, $entry_fee, $description, $requirements, $timeline_time, $timeline_title, $timeline_desc, $discord_url, $twitch_url){
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
            $sqlEvent = "INSERT INTO evenement (name, date_debut, event_time, description, type_evenement, statut, numbre_membre, lieu, club_id, registration_start, registration_end, event_photo, entry_fee, requirements, discord_url, twitch_url)             
            VALUES (:name, :date_debut, :event_time, :description, :type_evenement, :statut, :numbre_membre, :lieu, :club_id, :registration_start, :registration_end, :event_photo, :entry_fee, :requirements, :discord_url, :twitch_url)";
            
            $stmt = $this->conn->prepare($sqlEvent);
            $stmt->bindParam(":name", $name_event);
            $stmt->bindParam(":date_debut", $event_date);
            $stmt->bindParam(":event_time", $event_time);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":type_evenement", $type_event);
            $stmt->bindParam(":statut", $status);
            $stmt->bindParam(":numbre_membre", $max_participants);
            $stmt->bindParam(":lieu", $location);
            $stmt->bindParam(":club_id", $club_id);
            $stmt->bindParam(":registration_start", $registration_start);
            $stmt->bindParam(":registration_end", $registration_end);
            $stmt->bindParam(":event_photo", $event_photo);
            $stmt->bindParam(":entry_fee", $entry_fee);
            $stmt->bindParam(":requirements", $requirements);
            $stmt->bindParam(":discord_url", $discord_url);
            $stmt->bindParam(":twitch_url", $twitch_url);
            
            $isEventInserted = $stmt->execute();
            $event_id = $this->conn->lastInsertId();
            
            if (!$isEventInserted || !$event_id) {
                throw new Exception("Failed to insert event. event ID is null.");
            }
            
            $attachToProgramme = $this->attachEventToProgramme($event_id, $timeline_time, $timeline_title, $timeline_desc, $event_date);
            
            if (!$attachToProgramme) {
                throw new Exception("Failed to attach progamme.");
            }
            
            $this->conn->commit();
            return $event_id;
        } catch (PDOException $e){
            $this->conn->rollBack();
            echo "Error adding event: " . $e->getMessage();
            return null;
        }
    }
    
    // Ajout du paramètre event_date pour combiner avec timeline_time
    private function attachEventToProgramme($event_id, $timeline_time, $timeline_title, $timeline_desc, $event_date){
        try {
            // Si les tableaux de timeline sont vides
            if (empty($timeline_time)) {
                return true; // Aucun programme à ajouter, mais ce n'est pas une erreur
            }
            
            // Vérifier s'il s'agit d'un tableau ou non
            if (!is_array($timeline_time)) {
                $timeline_time = [$timeline_time];
                $timeline_title = [$timeline_title];
                $timeline_desc = [$timeline_desc];
            }
            
            // Récupérer le type de colonne timeline_time
            $columnTypeQuery = "SHOW COLUMNS FROM evenement_programme LIKE 'timeline_time'";
            $stmt = $this->conn->query($columnTypeQuery);
            $columnInfo = $stmt->fetch(PDO::FETCH_ASSOC);
            $columnType = $columnInfo['Type'];
            
            $sqlProgramme = "INSERT INTO evenement_programme (timeline_time, timeline_title, timeline_desc, event_id) VALUES (:timeline_time, :timeline_title, :timeline_desc, :event_id)";
            $stmt = $this->conn->prepare($sqlProgramme);
            
            // Traitement des tableaux pour chaque élément de programme
            for ($i = 0; $i < count($timeline_time); $i++) {
                if (isset($timeline_time[$i]) && isset($timeline_title[$i]) && isset($timeline_desc[$i])) {
                    // Formatage du temps en fonction du type de colonne dans la base de données
                    if (strpos(strtolower($columnType), 'datetime') !== false || strpos(strtolower($columnType), 'date') !== false) {
                        // Si c'est une colonne de type DATE ou DATETIME, on combine la date de l'événement avec l'heure
                        $formattedTime = date('Y-m-d', strtotime($event_date)) . ' ' . $timeline_time[$i];
                    } elseif (strpos(strtolower($columnType), 'time') !== false) {
                        // Si c'est une colonne de type TIME, on s'assure que le format est correct (HH:MM:SS)
                        $timeValue = $timeline_time[$i];
                        if (strlen($timeValue) === 5) { // Format HH:MM, ajouter :00 pour les secondes
                            $formattedTime = $timeValue . ':00';
                        } else {
                            $formattedTime = $timeValue;
                        }
                    } else {
                        // Pour tout autre type de colonne, on utilise l'heure telle quelle (comme chaîne)
                        $formattedTime = $timeline_time[$i];
                    }
                    
                    $stmt->bindParam(":timeline_time", $formattedTime);
                    $stmt->bindParam(":timeline_title", $timeline_title[$i]);
                    $stmt->bindParam(":timeline_desc", $timeline_desc[$i]);
                    $stmt->bindParam(":event_id", $event_id);
                    $stmt->execute();
                }
            }
            
            return true;
        } catch (PDOException $e){
            echo "Error attaching event to programme: " . $e->getMessage();
            // Afficher le contenu du tableau pour le débogage
            echo "<pre>";
            var_dump($timeline_time);
            echo "</pre>";
            echo "timeline_time" . $columnType;
            return false;
        }
    }
}
?>