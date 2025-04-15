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
    public function fetchAllEvents($user_id){
        $sql = "SELECT evenement.id , evenement.name , evenement.date_debut , evenement.date_fin , evenement.lieu , evenement.statut ,evenement.description,evenement.type_evenement,evenement.numbre_membre,evenement.registration_start,evenement.registration_end,evenement.event_time,evenement.event_photo,evenement.entry_fee,evenement.requirements,evenement.discord_url,evenement.twitch_url,GROUP_CONCAT(evenement_programme.timeline_time),GROUP_CONCAT(evenement_programme.timeline_title),GROUP_CONCAT(evenement_programme.timeline_desc)
        , count(inscription_evenement.membre_id) as number_participants FROM evenement
        INNER JOIN club ON evenement.club_id = club.id
        INNER JOIN evenement_programme ON evenement_programme.event_id = evenement.id
        LEFT JOIN inscription_evenement ON inscription_evenement.evenement_id = evenement.id
        INNER JOIN organisateur ON organisateur.club_id = evenement.club_id
        WHERE organisateur.user_id = :user_id
        GROUP BY evenement.id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id",$user_id);
        $stmt->execute();
        $evenement = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $evenement;
    }

    public function addEvent($user_id, $name_event, $registration_start, $registration_end, $type_event, $status, $location, $event_date, $event_time, $event_photo, $max_participants, $entry_fee, $description, $requirements, $timeline_time, $timeline_title, $timeline_desc, $discord_url, $twitch_url){
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
            
            for ($i = 0; $i < count($timeline_time); $i++) {
                if (isset($timeline_time[$i]) && isset($timeline_title[$i]) && isset($timeline_desc[$i])) {
                    if (strpos(strtolower($columnType), 'datetime') !== false || strpos(strtolower($columnType), 'date') !== false) {
                        $formattedTime = date('Y-m-d', strtotime($event_date)) . ' ' . $timeline_time[$i];
                    } elseif (strpos(strtolower($columnType), 'time') !== false) {
                        $timeValue = $timeline_time[$i];
                        if (strlen($timeValue) === 5) { 
                            $formattedTime = $timeValue . ':00';
                        } else {
                            $formattedTime = $timeValue;
                        }
                    } else {
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

    public function cancelEvenement($user_id, $event_id) {
        $query = "SELECT club_id FROM organisateur WHERE user_id=:user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        $club_idd = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$club_idd) {
            return false; 
        }
        $club_id = $club_idd["club_id"];
    
        $sqlCancel = "UPDATE evenement SET statut = :statut WHERE club_id = :club_id AND id = :event_id";
        $stmt = $this->conn->prepare($sqlCancel);
        $status = "cancelled";
        $stmt->bindParam(":statut", $status);
        $stmt->bindParam(":club_id", $club_id);
        $stmt->bindParam(":event_id", $event_id);
        $result = $stmt->execute();
        if ($result && $stmt->rowCount() > 0) {
            $sqlInscription = "DELETE FROM inscription_evenement WHERE evenement_id  = :event_id";
            $stmt = $this->conn->prepare($sqlInscription);
            $stmt->bindParam(":event_id",$event_id);
            $result = $stmt->execute();
            if ($result && $stmt->rowCount() > 0){
                return true; 
            }
        } else {
            return false; 
        }
    }

    public function withoutreimburseMembersForEvent($user_id, $event_id) {
        try {
            $this->conn->beginTransaction();
            
            $sqlMember = "SELECT membre_id FROM inscription_evenement WHERE evenement_id = :event_id";
            $stmt = $this->conn->prepare($sqlMember);
            $stmt->bindParam(":event_id", $event_id);
            $stmt->execute();
            $inscription_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $success_count = 0;
            
            foreach ($inscription_data as $data) {
                $member_id = $data['membre_id'];
                $sqlEvents = "SELECT frais_inscription FROM inscription_evenement WHERE membre_id = :member_id AND evenement_id = :event_id";
                $stmt = $this->conn->prepare($sqlEvents);
                $stmt->bindParam(":member_id", $member_id);
                $stmt->bindParam(":event_id", $event_id);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$result) {
                    continue; 
                }
                
                $frais_inscription = $result['frais_inscription'];
                $current_solde_data = $this->GetSolde($member_id);
                
                if (!$current_solde_data || !isset($current_solde_data['solde'])) {
                    continue; 
                }
                
                $current_solde = $current_solde_data['solde'];
                $new_solde = $current_solde;
                
                if ($this->logReimbursement($member_id, $event_id, $frais_inscription, $current_solde, $new_solde, $user_id)) {
                    $success_count++; 
                }
            }
            
            if ($success_count == 0 && count($inscription_data) > 0) {
                $this->conn->rollBack();
                return 0;
            }
            
            $this->conn->commit();     
            return $this->cancelEvenement($user_id, $event_id);
            
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error in withoutreimburseMembersForEvent: " . $e->getMessage());
            return 0;
        }
    }

    public function reimburseMembersForEvent($event_id) {
        try {
            $this->conn->beginTransaction();
            
            $sqlMember = "SELECT membre_id FROM inscription_evenement WHERE evenement_id = :event_id";
            $stmt = $this->conn->prepare($sqlMember);
            $stmt->bindParam(":event_id", $event_id);
            $stmt->execute();
            $inscription_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $success_count = 0;
            foreach ($inscription_data as $data) {
                $member_id = $data['membre_id'];
                $sqlEvents = "SELECT frais_inscription FROM inscription_evenement WHERE membre_id = :member_id AND evenement_id = :event_id";
                $stmt = $this->conn->prepare($sqlEvents);
                $stmt->bindParam(":member_id", $member_id);
                $stmt->bindParam(":event_id", $event_id);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$result) {
                    continue; 
                }
                $frais_inscription = $result['frais_inscription'];
                $current_solde_data = $this->GetSolde($member_id);
                if (!$current_solde_data || !isset($current_solde_data['solde'])) {
                    continue; 
                }
                $current_solde = $current_solde_data['solde'];
                $new_solde = $current_solde + $frais_inscription;
                if ($this->updateSolde($member_id, $new_solde)) {
                    $this->logReimbursement($member_id, $event_id, $frais_inscription, $current_solde, $new_solde);
                    $success_count++;
                }
            }
            
            if ($success_count == 0 && count($inscription_data) > 0) {
                $this->conn->rollBack();
                return 0;
            }
            
            $this->conn->commit();     
            return $success_count;
            
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return 0;
        }
    }
    
    private function logReimbursement($member_id, $event_id, $frais_inscription, $current_solde, $new_solde) {
        try {
            $sqlOrganisateur = "SELECT user_id FROM organisateur INNER JOIN evenement ON evenement.club_id = organisateur.club_id WHERE evenement.id = :event_id";
            $stmt = $this->conn->prepare($sqlOrganisateur);
            $stmt->bindParam(":event_id", $event_id);
            $stmt->execute();
            $organisateur = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$organisateur || !isset($organisateur["user_id"])) {
                error_log("No organizer found for event ID: $event_id");
                return false;
            }
            
            $organisateur_id = $organisateur["user_id"];

            if ($current_solde == $new_solde){
                $transaction_type = 'Sans reimbursement';
            } else {
                $transaction_type = 'reimbursement';
            }

            $sql = "INSERT INTO transaction_log (member_id, event_id, amount, old_balance, new_balance, transaction_type, created_by) VALUES (:member_id, :event_id, :amount, :old_balance, :new_balance, :transaction_type, :created_by)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":member_id", $member_id);
            $stmt->bindParam(":event_id", $event_id);
            $stmt->bindParam(":amount", $frais_inscription);
            $stmt->bindParam(":old_balance", $current_solde);
            $stmt->bindParam(":new_balance", $new_solde);
            $stmt->bindParam(":transaction_type", $transaction_type);
            $stmt->bindParam(":created_by", $organisateur_id);
            $result = $stmt->execute();
            
            if (!$result) {
                $error = $stmt->errorInfo();
                error_log("Failed to insert transaction log: " . $error[2]);
                return false;
            }

            $notification = $this->insertNotification($member_id, $event_id, $transaction_type, $frais_inscription);
            return true;
        } 
        catch (PDOException $e) {
            error_log("Exception in logReimbursement: " . $e->getMessage());
            return false;
        }
    }
    
    private function insertNotification($member_id, $event_id, $transaction_type, $amount) {
        try {
            $sql = "SELECT user_id FROM membre WHERE id = :member_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":member_id", $member_id);
            $stmt->execute();
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user_data || !isset($user_data['user_id'])) {
                error_log("Failed to get user_id for member_id: $member_id");
                return false;
            }
            
            $user_id = $user_data['user_id'];
            $sql = "SELECT name FROM evenement WHERE id = :event_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":event_id", $event_id);
            $stmt->execute();
            $event_data = $stmt->fetch(PDO::FETCH_ASSOC);
            $event_name = $event_data['name'];

            if ($transaction_type == 'reimbursement') {
                $message = "Vous avez été remboursé(e) de $amount € pour l'événement \"$event_name\".";
                $notification_type = 'reimbursement';
            } else {
                $message = "L'événement \"$event_name\" a été annulé sans remboursement.";
                $notification_type = 'event_cancellation';
            }
            
            $sql = "INSERT INTO notifications (user_id, message, type) VALUES (:user_id, :message, :type)";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(":message", $message);
            $stmt->bindParam(":type", $notification_type);
            
            $result = $stmt->execute();
            
            if (!$result) {
                $error = $stmt->errorInfo();
                error_log("Failed to insert notification: " . print_r($error, true));
                return false;
            }
            
            return true;
        }
        catch (PDOException $e) {
            error_log("Exception in insertNotification: " . $e->getMessage());
            return false;
        }
    }

    private function GetSolde($member_id) {
        $user_id = $this->GetUserId($member_id);
        $sqlSolde = "SELECT solde FROM compte WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sqlSolde);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    private function GetUserId($member_id) {
        $sql = "SELECT user_id FROM membre WHERE id = :member_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":member_id", $member_id);
        $stmt->execute();
        $id_member = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$id_member) {
            return null;
        }
        return $id_member["user_id"];
    }
    
    private function updateSolde($member_id, $new_solde) {
        $user_id = $this->GetUserId($member_id); 
        if (!$user_id) {
            return false;
        }
        $sqlUpdateSolde = "UPDATE compte SET solde = :solde WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sqlUpdateSolde);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":solde", $new_solde);
        $stmt->execute();
        return $stmt->rowCount() > 0; 
    }

}
?>