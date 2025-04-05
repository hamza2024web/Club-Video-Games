<?php
namespace App\Repository;

use Config\Database;
use Exception;
use PDO;

class PaymentRepository {
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function savePayement($user_id, $game_id, $order_id) {
        try {
    
            // Decode game IDs and order IDs
            $gameIds = json_decode($game_id, true);
            $orderIds = json_decode($order_id, true);
            
            // Check if arrays have same length
            if (count($gameIds) !== count($orderIds)) {
                throw new Exception("Mismatch between game and order IDs");
            }
            
            // Prepare order insertion statement
            $orderStmt = $this->conn->prepare("INSERT INTO orders (jeu_id, order_id, user_id) VALUES (:game_id, :order_id, :user_id)");
            
            $results = [];
            
            // Start a database transaction
            $this->conn->beginTransaction();
            
            // Process each game
            for ($i = 0; $i < count($gameIds); $i++) {
                // Bind parameters for order insertion
                $orderStmt->bindParam(":game_id", $gameIds[$i], PDO::PARAM_INT);
                $orderStmt->bindParam(":order_id", $orderIds[$i], PDO::PARAM_STR);
                $orderStmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
                $orderStmt->execute();
                
                // Récupérer l'ID de la dernière insertion
                $lastId = $this->conn->lastInsertId();
                $results[] = $lastId;
                
                // Prepare and execute stock update for each game
                $stockStmt = $this->conn->prepare("
                    UPDATE jeux 
                    SET stock = GREATEST(0, stock - 1) 
                    WHERE id = :id AND stock > 0
                ");
                $stockStmt->bindParam(":id", $gameIds[$i], PDO::PARAM_INT);
                $stockResult = $stockStmt->execute();
                
                // Check if stock update was successful
                if ($stockStmt->rowCount() === 0) {
                    throw new Exception("Stock update failed for game ID: " . $gameIds[$i]);
                }
            }
            
            // Commit the transaction
            $this->conn->commit();
            
            return $results;
        } catch (Exception $e) {
            // Rollback the transaction in case of any error
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            
            // Log the error (replace with your logging mechanism)
            error_log("Payment save error: " . $e->getMessage());
            
            return false;
        }
    }
    public function getUserPurchasedGames($user_id){
        $sql = "SELECT jeu_id FROM orders WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>