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

    public function savePayement($user_id, $game_id, $order_id, $price) {
        try {
            $gameIds = json_decode($game_id, true);
            $orderIds = json_decode($order_id, true);
            
            // Fix for price handling - check if it's already a number
            if (is_numeric($price)) {
                // Single price for all items
                $prices = array_fill(0, count($gameIds), $price);
            } else {
                // Try to decode as JSON array
                $prices = json_decode($price, true);
                
                // If decoding failed or result isn't an array, handle the error
                if (!is_array($prices)) {
                    throw new Exception("Invalid price format: " . $price);
                }
            }
            
            // Verify arrays have matching counts
            if (count($gameIds) !== count($orderIds)) {
                throw new Exception("Mismatch between game and order IDs");
            }
            
            // Make sure we have a price for each game
            if (count($gameIds) !== count($prices)) {
                // If we have a single price value for multiple games, replicate it
                if (count($prices) === 1 && count($gameIds) > 1) {
                    $singlePrice = $prices[0];
                    $prices = array_fill(0, count($gameIds), $singlePrice);
                } else {
                    throw new Exception("Mismatch between games and prices");
                }
            }
            
            $orderStmt = $this->conn->prepare("INSERT INTO orders (jeu_id, order_id, user_id, price) VALUES (:game_id, :order_id, :user_id, :price)");
            
            $results = [];
            
            $this->conn->beginTransaction();
            
            // Process each game
            for ($i = 0; $i < count($gameIds); $i++) {
                // Bind parameters for order insertion
                $orderStmt->bindParam(":game_id", $gameIds[$i], PDO::PARAM_INT);
                $orderStmt->bindParam(":order_id", $orderIds[$i], PDO::PARAM_STR);
                $orderStmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
                $orderStmt->bindParam(":price", $prices[$i], PDO::PARAM_STR); // Changed to PARAM_STR for float values
                $orderStmt->execute();
                
                // Get the ID of the last insertion
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
            
            // Log the error
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

    public function GetSolde($user_id) {
        $sql = "SELECT solde FROM compte WHERE user_id = :user_id";
        $pricestmt = $this->conn->prepare($sql);
        $pricestmt->bindParam(":user_id", $user_id);
        $pricestmt->execute();
        $price = $pricestmt->fetch(PDO::FETCH_ASSOC);
        return $price['solde'];
    }
    public function updateUserSolde($user_id, $newSolde) {
        try {
            $sql = "UPDATE compte SET solde = :solde WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":solde", $newSolde, PDO::PARAM_STR);
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Update solde error: " . $e->getMessage());
            return false;
        }
    }
}

?>