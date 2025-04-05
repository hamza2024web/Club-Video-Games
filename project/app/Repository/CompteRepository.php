<?php
namespace App\Repository;

use Config\Database;
use PDO;
use PDOException;

class CompteRepository {
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    public function getSold($user_id){
        $sql = "SELECT solde FROM compte WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['solde'] : 0.00;
    }
    public function rechargerCompte($user_id, $montant) {
        try {
            $sql = "UPDATE compte SET solde = solde + :montant WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT); 
            $stmt->bindParam(':montant', $montant, PDO::PARAM_STR);
            $result = $stmt->execute();
            
            if ($result && $stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // Loguer l'erreur en développement
            error_log("Erreur de rechargement: " . $e->getMessage());
            return false;
        }
    }
}
?>