<?php
namespace App\Repository;

use Config\Database;
use PDO;

class StaticAdminRepository {
    private $conn;
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function CountGames(){
        $sql = "SELECT COUNT(jeux.id)  as number_games FROM jeux";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function TatalActiveMembers(){
        $sql = "SELECT COUNT(users.id) AS users FROM users WHERE users.status = 'Activation' AND role_id != 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function CountActiveSession(){
        $sql = "SELECT COUNT(evenement.id) AS number_evenement FROM evenement WHERE evenement.statut = 'In Progress' OR evenement.statut = 'Open'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function countPendingApprovale(){
        $sql = "SELECT COUNT(evenement.id) AS pending_evenemet FROM evenement WHERE evenement.statut = 'Pending'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function Count_Partcipants(){
        $sql = "SELECT COUNT(inscription_tournoi.membre_id) as participants_tournoi , COUNT(inscription_evenement.membre_id) as participants_events FROM inscription_tournoi , inscription_evenement";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function Count_Events($user_id){
        $sqlClub = "SELECT club_id FROM organisateur WHERE user_id = :user_id";
        $stmtClub = $this->conn->prepare($sqlClub);
        $stmtClub->bindParam(":user_id",$user_id);
        $stmtClub->execute();
        $club_idd = $stmtClub->fetch(PDO::FETCH_ASSOC);
        $club_id = $club_idd["club_id"];
        $sql = "SELECT COUNT(evenement.id) as number_events FROM evenement WHERE club_id = :club_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":club_id",$club_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function Count_games($user_id){
        $sql = "SELECT COUNT(orders.order_id) as number_jeux FROM orders WHERE user_id=:user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id",$user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function count_tournoi($user_id){
        $sql = "SELECT COUNT(inscription_tournoi.membre_id) AS tournoi_inscrit FROM inscription_tournoi
        INNER JOIN membre ON membre.id = inscription_tournoi.membre_id
        WHERE membre.user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id",$user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}
?>