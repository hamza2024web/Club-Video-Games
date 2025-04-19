<?php
namespace App\Repository;
use Config\Database;
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
        INNER JOIN inscription_tournoi ON inscription_tournoi.tournoi_id = tournoi.id
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
}
