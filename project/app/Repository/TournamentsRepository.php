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
        $sql = "SELECT tournoi.id ,tournoi.name ,tournoi.date_de_debut , tournoi.frais_inscription , tournoi.image , tournoi.numbre_membre ,tournoi.statut , tournoi.format_tournoi FROM tournoi";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $tournois = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $tournois;
    }
    public function Inscription_member($user_id,$tournoi_id){
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
}
?>