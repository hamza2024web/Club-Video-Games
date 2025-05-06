<?php
namespace App\Repository;

use Config\Database;
use PDO;

class EvenementProgrammeRepository {
    private $conn;
    
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getEventById($event_id){
        $sql = "SELECT * FROM evenement_programme WHERE event_id=:event_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":event_id",$event_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>