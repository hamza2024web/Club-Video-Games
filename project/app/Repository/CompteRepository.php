<?php
namespace App\Repository;

use Config\Database;
use PDO;

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
        $sql = "UPDATE compte SET solde = solde + :montant WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':montant', $montant, PDO::PARAM_STR);
        return $stmt->execute();
    }
}
?>