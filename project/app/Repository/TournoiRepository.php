<?php
namespace App\Repository;
use Config\Database;
use Exception;
use PDO;
use PDOException;

class TournoiRepository {
    private $conn ;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    public function getTournoiInformations($user_id){
        $sql = "SELECT tournoi.id,tournoi.name,tournoi.date_de_debut,tournoi.date_de_fin,tournoi.numbre_membre,tournoi.statut,tournoi.regles,tournoi.description,jeux.nom_de_jeu
        ,tournoi.prix_total ,tournoi.date_ouverture_inscription,tournoi.date_cloture_inscription,tournoi.frais_inscription,tournoi.discord,tournoi.twitch,tournoi.image , COUNT(inscription_tournoi.membre_id) AS number_inscription FROM tournoi
        INNER JOIN jeux ON jeux.id = tournoi.jeu_id
        INNER JOIN evenement ON evenement.id = tournoi.event_id
        LEFT JOIN inscription_tournoi ON inscription_tournoi.tournoi_id = tournoi.id
        INNER JOIN organisateur ON organisateur.club_id = evenement.club_id
        WHERE organisateur.user_id = :user_id
        GROUP BY tournoi.id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id",$user_id);
        $stmt->execute();
        $tournoi = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $tournoi;
    }
    public function addTounroi($user_id, $name, $start_date, $end_date, $max_participants, $status, $rules, $game, $format, $description, $prix_total, $prize_first, $prize_second, $prize_third, $registration_start, $registration_end, $registration_fee, $discord_url, $stream_url, $tournament_photo) {
        try {            
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
    
            // Débuter une transaction
            $this->conn->beginTransaction();
            
            // Insérer dans la table événement
            $sqlEvent = "INSERT INTO evenement (name, date_debut, date_fin, description, type_evenement, statut, numbre_membre, club_id) 
                         VALUES (:name, :date_debut, :date_fin, :description, :type_evenement, :statut, :numbre_membre, :club_id)";
            $stmt = $this->conn->prepare($sqlEvent);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":date_debut", $start_date);
            $stmt->bindParam(":date_fin", $end_date);
            $stmt->bindParam(":description", $description);
            $type = "Tournament"; 
            $stmt->bindParam(":type_evenement", $type);
            $stmt->bindParam(":statut", $status);
            $stmt->bindParam(":numbre_membre", $max_participants);
            $stmt->bindParam(":club_id", $club_id);
            $stmt->execute();
            
            $event_id = $this->conn->lastInsertId();
    
            // Insérer dans la table tournoi
            $sql = "INSERT INTO tournoi (name, date_de_debut, date_de_fin, numbre_membre, statut, regles, jeu_id, event_id, format_tournoi, description, prix_total, premier_place, deuxieme_place, troisieme_place, date_ouverture_inscription, date_cloture_inscription, frais_inscription, discord, twitch, image) 
                   VALUES (:name, :date_de_debut, :date_de_fin, :numbre_membre, :statut, :regles, :jeu_id, :event_id, :format_tournoi, :description, :prix_total, :premier_place, :deuxieme_place, :troisieme_place, :date_ouverture_inscription, :date_cloture_inscription, :frais_inscription, :discord, :twitch, :image)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":date_de_debut", $start_date);
            $stmt->bindParam(":date_de_fin", $end_date);
            $stmt->bindParam(":numbre_membre", $max_participants);
            $stmt->bindParam(":statut", $status);
            $stmt->bindParam(":regles", $rules);
            $stmt->bindParam(":jeu_id", $game);
            $stmt->bindParam(":event_id", $event_id);
            $stmt->bindParam(":format_tournoi", $format);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":prix_total", $prix_total);
            $stmt->bindParam(":premier_place", $prize_first);
            $stmt->bindParam(":deuxieme_place", $prize_second);
            $stmt->bindParam(":troisieme_place", $prize_third);
            $stmt->bindParam(":date_ouverture_inscription", $registration_start);
            $stmt->bindParam(":date_cloture_inscription", $registration_end);
            $stmt->bindParam(":frais_inscription", $registration_fee);
            $stmt->bindParam(":discord", $discord_url);
            $stmt->bindParam(":twitch", $stream_url);
            $stmt->bindParam(":image", $tournament_photo);
            $stmt->execute(); 
            
            // Valider la transaction
            $this->conn->commit();
            return true;
            
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $this->conn->rollBack();
                
            // Enregistrer l'erreur détaillée
            error_log("Erreur PDO dans addTounroi: " . $e->getMessage());
            
            // Pour le débogage temporaire, vous pouvez également afficher l'erreur
            // (à supprimer en production)
            echo "Erreur: " . $e->getMessage();
            
            return false;
        }
    }

    public function GetTournoi($tournoi_id){
        $sql = "SELECT * FROM tournoi WHERE id = :tournoi_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":tournoi_id",$tournoi_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function getParticipants($tournoi_id){
        $sql = "SELECT users.id , users.name , membre.tag_name , membre.profile_photo FROM inscription_tournoi INNER JOIN membre ON inscription_tournoi.membre_id = membre.id INNER JOIN users ON users.id = membre.user_id WHERE inscription_tournoi.tournoi_id = :tournoi_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":tournoi_id",$tournoi_id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function saveMatche($match){
        try {
            $sql = "INSERT INTO matches (tournoi_id, round, match_number, participant1_id, participant1_name, participant2_id, participant2_name,scheduled_date, status) 
            VALUES (:tournoi_id, :round, :match_number, :participant1_id, :participant1_name, :participant2_id, :participant2_name,:scheduled_date, :status)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':tournoi_id', $match['tournoi_id']);
            $stmt->bindParam(':round', $match['round']);
            $stmt->bindParam(':match_number', $match['match_number']);
            $stmt->bindParam(':participant1_id', $match['participant1_id']);
            $stmt->bindParam(':participant1_name', $match['participant1_name']);
            $stmt->bindParam(':participant2_id', $match['participant2_id']);
            $stmt->bindParam(':participant2_name', $match['participant2_name']);
            $stmt->bindParam(':scheduled_date', $match['scheduled_date']);
            $stmt->bindParam(':status', $match['status']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error saving match: " . $e->getMessage());
            return false;
        }
    }

    public function getTournoiMatches($tournoi_id){
        $sql = "SELECT matches.* from matches INNER JOIN tournoi ON tournoi.id = matches.tournoi_id WHERE tournoi.id = :tournoi_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":tournoi_id",$tournoi_id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function updateMatchWithScores($tournoi_id, $match_id, $participant1_id, $participant1_score, $participant2_id, $participant2_score) {

        if ($participant1_score > $participant2_score) {
            $winner_id = $participant1_id;
        } elseif ($participant2_score > $participant1_score) {
            $winner_id = $participant2_id;
        } else {
            $winner_id = $participant1_id;
        }

        
        $status = 'completed';
        
        $sqlMatch = "UPDATE matches SET score_participant1 = :participant1_score, score_participant2 = :participant2_score, winner_id = :winner_id, status = :status WHERE id = :match_id AND tournoi_id = :tournoi_id";
        $stmt = $this->conn->prepare($sqlMatch);
        $stmt->bindParam(":participant1_score", $participant1_score);
        $stmt->bindParam(":participant2_score", $participant2_score);
        $stmt->bindParam(":winner_id", $winner_id);
        $stmt->bindParam(":status", $status); 
        $stmt->bindParam(":tournoi_id", $tournoi_id);
        $stmt->bindParam(":match_id", $match_id);
        $stmt->execute();
        
        $sqlGetMatch = "SELECT * FROM matches WHERE id = :match_id";
        $stmtGet = $this->conn->prepare($sqlGetMatch);
        $stmtGet->bindParam(":match_id", $match_id);
        $stmtGet->execute();
        
        return $stmtGet->fetch(PDO::FETCH_ASSOC);
    }

    public function verifyTheTournoiIsCompleted($tournoi_id, $match) {
        try {
            $currentRound = (int)$match["round"];
            
            $sql = "SELECT COUNT(*) as count FROM matches WHERE tournoi_id = :tournoi_id AND round > :current_round";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":tournoi_id", $tournoi_id);
            $stmt->bindParam(":current_round", $currentRound);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ((int)$result['count'] === 0 && $match['status'] === 'completed') {
                $this->conn->beginTransaction();
                
                try {
                    $sql = "SELECT t.premier_place, t.deuxieme_place, t.event_id, e.name as tournament_name FROM tournoi t JOIN evenement e ON t.event_id = e.id WHERE t.id = :tournoi_id";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindParam(":tournoi_id", $tournoi_id);
                    $stmt->execute();
                    $tournamentInfo = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if (!$tournamentInfo) {
                        throw new Exception("Tournament information not found");
                    }
                    
                    $event_id = $tournamentInfo['event_id'];
                    $tournament_name = $tournamentInfo['tournament_name'];
                    
                    $first_place_id = $match['winner_id'];
                    
                    if ($match['winner_id'] == $match['participant1_id']) {
                        $second_place_id = $match['participant2_id'];
                    } else {
                        $second_place_id = $match['participant1_id'];
                    }
                    
                    $sql = "SELECT membre.id FROM membre INNER JOIN users ON users.id = membre.user_id WHERE membre.user_id = :user_id";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindParam(":user_id", $first_place_id);
                    $stmt->execute();
                    $first_place_member = $stmt->fetch(PDO::FETCH_ASSOC);
                    $first_place_member_id = $first_place_member['id'];
                    $stmt->bindParam(":user_id", $second_place_id);
                    $stmt->execute();
                    $second_place_member = $stmt->fetch(PDO::FETCH_ASSOC);
                    $second_place_member_id = $second_place_member['id'];
                    
                    $sqlOrganisateur = "SELECT organisateur.user_id FROM organisateur
                    INNER JOIN evenement ON evenement.club_id = organisateur.club_id
                    INNER JOIN tournoi ON tournoi.event_id = evenement.id 
                    WHERE tournoi.id = :tournoi_id";
                    $stmt = $this->conn->prepare($sqlOrganisateur);
                    $stmt->bindParam(":tournoi_id",$tournoi_id);
                    $stmt->execute();
                    $organisateur_id = $stmt->fetch(PDO::FETCH_ASSOC);
                    $admin_id = $organisateur_id["user_id"];
                    
                    $first_solde = $this->GetSolde($first_place_id);
                    $second_solde = $this->GetSolde($second_place_id);
                    
                    $first_prize = (float)$tournamentInfo["premier_place"];
                    $second_prize = (float)$tournamentInfo["deuxieme_place"];
                    $newSolde1 = $first_solde + $first_prize;
                    $newSolde2 = $second_solde + $second_prize;
                    
                    $resultUpdate1 = $this->updateUserSolde($first_place_id, $newSolde1);
                    if ($resultUpdate1) {
                        $transaction_type = "tournament_prize";
                        $this->logTransaction(
                            $first_place_member_id, 
                            $event_id, 
                            $first_prize,
                            $first_solde,
                            $newSolde1,
                            $transaction_type,
                            $admin_id
                        );
                        
                        $message = "Félicitations! Vous avez remporté le tournoi \"{$tournament_name}\" et gagné {$first_prize}€ en récompense.";
                        $this->insertPrizeNotification($first_place_id, $message, "tournament_prize_first");
                    }
                    
                    $resultUpdate2 = $this->updateUserSolde($second_place_id, $newSolde2);
                    if ($resultUpdate2) {
                        $transaction_type = "tournament_prize";
                        $this->logTransaction(
                            $second_place_member_id,
                            $event_id,
                            $second_prize,
                            $second_solde,
                            $newSolde2,
                            $transaction_type,
                            $admin_id
                        );
                        
                        $message = "Félicitations! Vous avez terminé à la 2ème place du tournoi \"{$tournament_name}\" et gagné {$second_prize}€ en récompense.";
                        $this->insertPrizeNotification($second_place_id, $message, "tournament_prize_second");
                    }
                    
                    if ($resultUpdate1 && $resultUpdate2) {
                        $status = "Completed";
                        $sql = "UPDATE tournoi SET statut = :status WHERE id = :tournoi_id";
                        $stmt = $this->conn->prepare($sql);
                        $stmt->bindParam(":status", $status);
                        $stmt->bindParam(":tournoi_id", $tournoi_id);
                        $stmt->execute();
                        
                        $this->conn->commit();
                        return true;
                    } else {
                        throw new Exception("Failed to update user balances");
                    }
                } catch (Exception $e) {
                    $this->conn->rollBack();
                    error_log("Tournament completion error: " . $e->getMessage());
                    return false;
                }
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Error verifying tournament completion: " . $e->getMessage());
            return false;
        }
    }

    public function advanceWinnerToNextRound($tournoi_id, $match) {
        $currentRound = $match['round'];
        $currentMatchNumber = $match['match_number'];
        $winnerId = $match['winner_id'];

        $sqlWinnerName = "SELECT DISTINCT users.name FROM users
        INNER JOIN membre ON membre.user_id = users.id
        INNER JOIN inscription_tournoi ON inscription_tournoi.membre_id = membre.id
        INNER JOIN matches ON matches.tournoi_id = inscription_tournoi.tournoi_id
        WHERE users.id = :winner_id";
        $stmtWinner = $this->conn->prepare($sqlWinnerName);
        $stmtWinner->bindParam(":winner_id", $winnerId);
        $stmtWinner->execute();
        $winner = $stmtWinner->fetch(PDO::FETCH_ASSOC);
        $winnerName = $winner['name'];
        
        $nextRound = $currentRound + 1;
        $nextMatchNumber = ceil($currentMatchNumber / 2);
        
        $participantField = ($currentMatchNumber % 2 == 1) ? 'participant1' : 'participant2';
        
        $sqlUpdateNext = "UPDATE matches SET {$participantField}_id = :winner_id,{$participantField}_name = :winner_name WHERE tournoi_id = :tournoi_id AND round = :next_round AND match_number = :next_match_number";
        $stmtNext = $this->conn->prepare($sqlUpdateNext);
        $stmtNext->bindParam(":winner_id", $winnerId);
        $stmtNext->bindParam(":winner_name", $winnerName);
        $stmtNext->bindParam(":tournoi_id", $tournoi_id);
        $stmtNext->bindParam(":next_round", $nextRound);
        $stmtNext->bindParam(":next_match_number", $nextMatchNumber);
        $stmtNext->execute();
    }

    private function GetSolde($user_id) {
        try {
            $sql = "SELECT solde FROM compte WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                error_log("No balance found for user ID: " . $user_id);
                return 0; 
            }
            
            return (float)$result['solde'];
        } catch (Exception $e) {
            error_log("Error getting user balance: " . $e->getMessage());
            return 0;
        }
    }
    
    private function updateUserSolde($user_id, $newSolde) {
        try {
            $sql = "UPDATE compte SET solde = :solde WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":solde", $newSolde, PDO::PARAM_STR);
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Update balance error: " . $e->getMessage());
            return false;
        }
    }

    private function logTransaction($member_id, $event_id, $amount, $old_balance, $new_balance, $transaction_type, $created_by) {
        try {
            $sql = "INSERT INTO transaction_log (member_id, event_id, amount, old_balance, new_balance, transaction_type, created_by) 
                    VALUES (:member_id, :event_id, :amount, :old_balance, :new_balance, :transaction_type, :created_by)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":member_id", $member_id);
            $stmt->bindParam(":event_id", $event_id);
            $stmt->bindParam(":amount", $amount);
            $stmt->bindParam(":old_balance", $old_balance);
            $stmt->bindParam(":new_balance", $new_balance);
            $stmt->bindParam(":transaction_type", $transaction_type);
            $stmt->bindParam(":created_by", $created_by);
            
            $result = $stmt->execute();
            
            if (!$result) {
                $error = $stmt->errorInfo();
                error_log("Failed to insert transaction log: " . $error[2]);
                return false;
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error logging transaction: " . $e->getMessage());
            return false;
        }
    }

    private function insertPrizeNotification($user_id, $message, $notification_type) {
        try {
            $sql = "INSERT INTO notifications (user_id, message, type, is_read) VALUES (:user_id, :message, :type, :is_read)";
            $stmt = $this->conn->prepare($sql);
            $is_read = 0;
            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(":message", $message);
            $stmt->bindParam(":type", $notification_type);
            $stmt->bindParam(":is_read", $is_read);
            $result = $stmt->execute();

            if (!$result) {
                $error = $stmt->errorInfo();
                error_log("Failed to insert prize notification: " . print_r($error, true));
                return false;
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Exception in insertPrizeNotification: " . $e->getMessage());
            return false;
        }
    }
}
