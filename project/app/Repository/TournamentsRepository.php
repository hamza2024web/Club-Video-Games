<?php
namespace App\Repository;
use Config\Database;
use PDO;
use PDOException;

class TournamentsRepository {
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAllTournois(){
        $sql = "SELECT tournoi.id ,tournoi.name ,tournoi.date_de_debut , tournoi.frais_inscription , tournoi.image , tournoi.numbre_membre 
        ,tournoi.statut , tournoi.format_tournoi ,tournoi.regles , tournoi.description , tournoi.prix_total ,tournoi.premier_place , tournoi.deuxieme_place 
        ,tournoi.troisieme_place ,tournoi.date_ouverture_inscription,tournoi.date_cloture_inscription,tournoi.discord,tournoi.twitch,COUNT(inscription_tournoi.id) as nombre_participants ,jeux.nom_de_jeu as jeu FROM tournoi
        LEFT JOIN inscription_tournoi ON inscription_tournoi.tournoi_id = tournoi.id
        INNER JOIN jeux ON jeux.id = tournoi.jeu_id
        WHERE tournoi.statut != 'cancelled'
        GROUP BY  tournoi.id, tournoi.name, tournoi.date_de_debut, tournoi.frais_inscription, tournoi.image, tournoi.numbre_membre, tournoi.statut, tournoi.format_tournoi, tournoi.regles, tournoi.description, tournoi.prix_total, tournoi.premier_place, tournoi.deuxieme_place, tournoi.troisieme_place, tournoi.date_ouverture_inscription, tournoi.date_cloture_inscription, tournoi.discord, tournoi.twitch";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $tournois = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $tournois;
    }
    
    public function Inscription_Tournoi($user_id, $tournoi_id, $price) {
        $sqlMember = "SELECT id FROM membre WHERE user_id = :user_id";
        $stmtMember = $this->conn->prepare($sqlMember);
        $stmtMember->bindParam(":user_id", $user_id);
        $stmtMember->execute();
        $member = $stmtMember->fetch(PDO::FETCH_ASSOC);
        
        if (!$member) {
            return false;
        }
        
        $member_id = $member['id'];
        
        try {
            $this->conn->beginTransaction();
            
            $sqlInscription = "INSERT INTO inscription_tournoi (tournoi_id, membre_id, frais_inscription) VALUES (:tournoi_id, :member_id, :frais_inscription)";
            $stmtInsert = $this->conn->prepare($sqlInscription);
            $stmtInsert->bindParam(":tournoi_id", $tournoi_id);
            $stmtInsert->bindParam(":member_id", $member_id);
            $stmtInsert->bindParam(":frais_inscription", $price);
            $result = $stmtInsert->execute();
            
            if (!$result) {
                $this->conn->rollBack();
                return false;
            }

            $sqlTournoi = "SELECT numbre_membre, name FROM tournoi WHERE id = :tournoi_id";
            $stmtTournoi = $this->conn->prepare($sqlTournoi);
            $stmtTournoi->bindParam(":tournoi_id", $tournoi_id);
            $stmtTournoi->execute();
            $tournoi = $stmtTournoi->fetch(PDO::FETCH_ASSOC);
            
            if (!$tournoi) {
                $this->conn->rollBack();
                return false;
            }
            
            $max_participants = $tournoi["numbre_membre"];
            $tournoi_name = $tournoi["name"];

            $sqlCount = "SELECT COUNT(*) as participant_count FROM inscription_tournoi WHERE tournoi_id = :tournoi_id";
            $stmtCount = $this->conn->prepare($sqlCount);
            $stmtCount->bindParam(":tournoi_id", $tournoi_id);
            $stmtCount->execute();
            $count_result = $stmtCount->fetch(PDO::FETCH_ASSOC);
            $current_count = $count_result["participant_count"];
            
            if ($current_count == $max_participants) {
                $sqlProgress = "UPDATE tournoi SET statut = :statut WHERE id = :tournoi_id";
                $stmtUpdate = $this->conn->prepare($sqlProgress);
                $status = "In Progress";
                $stmtUpdate->bindParam(":statut", $status);
                $stmtUpdate->bindParam(":tournoi_id", $tournoi_id);
                $update_result = $stmtUpdate->execute();

                if (!$update_result) {
                    $this->conn->rollBack();
                    return false;
                }
                
                $sqlEventId = "SELECT event_id FROM tournoi WHERE id = :tournoi_id";
                $stmtEventId = $this->conn->prepare($sqlEventId);
                $stmtEventId->bindParam(":tournoi_id",$tournoi_id);
                $stmtEventId->execute();
                $event_idd = $stmtEventId->fetch(PDO::FETCH_ASSOC);
                $event_id = $event_idd['event_id'];

                $sqlClub = "SELECT club_id FROM evenement WHERE id = :event_id";
                $stmtClub = $this->conn->prepare($sqlClub);
                $stmtClub->bindParam(":event_id", $event_id);
                $stmtClub->execute();
                $clubData = $stmtClub->fetch(PDO::FETCH_ASSOC);
                
                if (!$clubData) {
                    $this->conn->rollBack();
                    return false;
                }
                
                $club_id = $clubData['club_id'];

                $sqlOrganizer = "SELECT user_id FROM organisateur WHERE club_id = :club_id";
                $stmtOrganizer = $this->conn->prepare($sqlOrganizer);
                $stmtOrganizer->bindParam(":club_id", $club_id);
                $stmtOrganizer->execute();
                $organizerData = $stmtOrganizer->fetch(PDO::FETCH_ASSOC);

                if (!$organizerData) {
                    $this->conn->rollBack();
                    return false;
                }
                
                $organizer_id = $organizerData['user_id'];

                $message = "Le tournoi $tournoi_name a atteint sa capacité maximale de {$max_participants} joueurs et a démarré! Connectez-vous pour voir votre planning des matchs.";
                $notificationType = "tournament_started";
                $is_read = 0;

                $sqlNotification = "INSERT INTO notifications (user_id, message, type, is_read) VALUES (:user_id, :message, :type, :is_read)";
                $stmtNotification = $this->conn->prepare($sqlNotification);
                $stmtNotification->bindParam(":user_id", $organizer_id);
                $stmtNotification->bindParam(":message", $message);
                $stmtNotification->bindParam(":type", $notificationType);
                $stmtNotification->bindParam(":is_read", $is_read);
                $stmtNotification->execute();

                $sqlParticipants = "SELECT m.user_id FROM inscription_tournoi it JOIN membre m ON it.membre_id = m.id WHERE it.tournoi_id = :tournoi_id";
                $stmtParticipants = $this->conn->prepare($sqlParticipants);
                $stmtParticipants->bindParam(":tournoi_id", $tournoi_id);
                $stmtParticipants->execute();
                $participants = $stmtParticipants->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($participants as $participant) {
                    if ($participant['user_id'] != $organizer_id) {
                        $stmtNotification = $this->conn->prepare($sqlNotification);
                        $stmtNotification->bindParam(":user_id", $participant['user_id']);
                        $stmtNotification->bindParam(":message", $message);
                        $stmtNotification->bindParam(":type", $notificationType);
                        $stmtNotification->bindParam(":is_read", $is_read);
                        $stmtNotification->execute();
                    }
                }
                
                error_log(date('Y-m-d H:i:s') . " - Tournament ID: $tournoi_id status changed to In Progress because max participants ($max_participants) reached");
            }
            
            $this->conn->commit();
            return true;
            
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Erreur PDO dans Inscription_Tournoi: " . $e->getMessage());
            return false;
        }
    }

    public function getTournoiInscri($user_id){
        $sql = "SELECT tournoi.id ,tournoi.name ,tournoi.date_de_debut , tournoi.image , tournoi.numbre_membre , tournoi.frais_inscription ,tournoi.statut , tournoi.format_tournoi ,tournoi.regles , tournoi.description , tournoi.prix_total ,tournoi.premier_place , tournoi.deuxieme_place ,tournoi.troisieme_place ,tournoi.discord,tournoi.twitch 
        ,jeux.nom_de_jeu as jeu , count(inscription_tournoi.tournoi_id) as number_participants FROM tournoi
        INNER JOIN inscription_tournoi ON inscription_tournoi.tournoi_id = tournoi.id
        INNER JOIN jeux ON jeux.id = tournoi.jeu_id
        INNER JOIN membre ON membre.id = inscription_tournoi.membre_id
        INNER JOIN users ON users.id = membre.user_id
        WHERE users.id = :user_id
        GROUP BY  tournoi.id, tournoi.name, tournoi.date_de_debut, tournoi.frais_inscription, tournoi.image, tournoi.numbre_membre, tournoi.statut, tournoi.format_tournoi, tournoi.regles, tournoi.description, tournoi.prix_total, tournoi.premier_place, tournoi.deuxieme_place, tournoi.troisieme_place, tournoi.date_ouverture_inscription, tournoi.date_cloture_inscription, tournoi.discord, tournoi.twitch";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id",$user_id);
        $stmt->execute();
        $inscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $inscriptions;
    }

    public function getMyCalendries($user_id){
        $sql = "SELECT matches.* FROM matches
		INNER JOIN tournoi ON tournoi.id = matches.tournoi_id
        INNER JOIN inscription_tournoi ON inscription_tournoi.tournoi_id = tournoi.id
        INNER JOIN membre ON membre.id = inscription_tournoi.membre_id
        INNER JOIN users ON users.id = membre.user_id
        WHERE users.id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id",$user_id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}
?>