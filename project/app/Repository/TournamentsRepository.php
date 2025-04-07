<?php
namespace App\Repository;
use Config\Database;
use PDO;

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
        ,tournoi.troisieme_place ,tournoi.date_ouverture_inscription,tournoi.date_cloture_inscription,tournoi.discord,tournoi.twitch,COUNT(*) as nombre_participants ,jeux.nom_de_jeu as jeu FROM tournoi
        LEFT JOIN inscription_tournoi ON inscription_tournoi.tournoi_id = tournoi.id
        INNER JOIN jeux ON jeux.id = tournoi.jeu_id
        GROUP BY  tournoi.id, tournoi.name, tournoi.date_de_debut, tournoi.frais_inscription, tournoi.image, tournoi.numbre_membre, tournoi.statut, tournoi.format_tournoi, tournoi.regles, tournoi.description, tournoi.prix_total, tournoi.premier_place, tournoi.deuxieme_place, tournoi.troisieme_place, tournoi.date_ouverture_inscription, tournoi.date_cloture_inscription, tournoi.discord, tournoi.twitch";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $tournois = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $tournois;
    }
    public function Inscription_Tournoi($user_id,$tournoi_id){
        $sqlMember = "SELECT id FROM membre WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sqlMember);
        $stmt->bindParam(":user_id",$user_id);
        $stmt->execute();
        $member_id = $stmt->fetch(PDO::FETCH_ASSOC);

        $sqlInscription = "INSERT INTO inscription_tournoi (tournoi_id,member_id) VALUES (:tournoi_id , :member_id)";
        $stmt = $this->conn->prepare($sqlInscription);
        $stmt->bindParam(":tournoi_id",$tournoi_id);
        $stmt->bindParam(":member_id",$member_id);
        $stmt->execute();
        $insription=  $stmt->fetch(PDO::FETCH_ASSOC);
        if ($insription){
            return true;
        } else {
            return false;
        }
    }
    public function getTournoiInscri($user_id){
        $sql = "SELECT tournoi.id ,tournoi.name ,tournoi.date_de_debut , tournoi.image , tournoi.numbre_membre ,tournoi.statut , tournoi.format_tournoi ,tournoi.regles , tournoi.description , tournoi.prix_total ,tournoi.premier_place , tournoi.deuxieme_place ,tournoi.troisieme_place ,tournoi.discord,tournoi.twitch FROM tournoi
        INNER JOIN inscription_tournoi ON inscription_tournoi.tournoi_id = tournoi.id
        INNER JOIN membre ON membre.id = inscription_tournoi.membre_id
        INNER JOIN users ON users.id = membre.user_id
        WHERE users.id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id",$user_id);
        $stmt->execute();
        $inscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $inscriptions;
    }
}
?>