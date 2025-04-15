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
}
?>