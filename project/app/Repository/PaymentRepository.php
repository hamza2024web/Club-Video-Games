<?php
namespace App\Repository;

use Config\Database;
use PDO;

class PaymentRepository {
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function savePayement($user_id,$game_id,$order_id) {
        $gameIds = json_decode($game_id);
        $orderIds = json_decode($order_id);
        
        if (count($gameIds) !== count($orderIds)) {
            return false; 
        }
        
        $stmt = $this->conn->prepare("INSERT INTO orders (jeu_id, order_id, user_id) VALUES (:game_id, :order_id,:user_id)");
        
        $results = [];
        
        for ($i = 0; $i < count($gameIds); $i++) {
            $stmt->bindParam(":game_id", $gameIds[$i]);
            $stmt->bindParam(":order_id", $orderIds[$i]);
            $stmt->bindParam(":user_id",$user_id);
            $stmt->execute();
            
            // Récupérer l'ID de la dernière insertion
            $lastId = $this->conn->lastInsertId();
            $results[] = $lastId;
        }
        
        return $results;
    }
}

?>