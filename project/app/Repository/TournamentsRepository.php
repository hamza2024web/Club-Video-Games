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
        $sql = "SELECT tournoi.name ,tournoi.date_de_debut , tournoi.frais_inscription , tournoi.image , tournoi.numbre_membre ,tournoi.statut , tournoi.format_tournoi FROM tournoi";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $tournois = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $tournois;
    }
}
?>