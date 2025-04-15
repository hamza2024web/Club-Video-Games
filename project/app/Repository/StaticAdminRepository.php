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
}
?>