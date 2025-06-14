<?php
namespace App\Repository;
use Config\Database;
use App\Models\Users;
use PDO;


class MembreAndOrgan {
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    public function setMembreAndOrganisateur($role, $name, $email, $password, $naissance, $club) {
        try {
            if ($role === 'membre'){
                $status = "Activation";
            } elseif ($role === 'organisateur'){
                $status = "Not Active";
            }    
            
            $sql = "SELECT id FROM role WHERE name = :role";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":role", $role);
            $stmt->execute();
            $roleData = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$roleData) {
                throw new \Exception("Role not found.");
            }
            $role_id = $roleData['id'];
    
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
            $query = "INSERT INTO users (`name`, email, `password`, `role_id`, `status`) 
                      VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$name, $email, $hashedPassword, $role_id, $status]);
            $userId = $this->conn->lastInsertId();
    
            if ($role === 'membre') {
                $this->addMembre($userId, ['date_naissance' => $naissance]);
            } elseif ($role === 'organisateur') {
                $this->addOrganisateur($userId, ['name' => $club]);
            }
    
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            // Après avoir inséré dans la table users et récupéré l'ID

            // Initialiser le compte avec un solde de 0
            $sql = "INSERT INTO compte (user_id, solde) VALUES (:user_id, 0.00)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
    
            return new Users($userData['email'], $userData['password'], $role, $userData['status']);
        } catch (\PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return null;
        } catch (\Exception $e) {
            error_log("Error: " . $e->getMessage());
            return null;
        }
    }
    

    private function addMembre($userId ,$data){
        $sql = "INSERT INTO membre (date_naissance ,user_id) VALUES (? , ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$data['date_naissance'] , $userId]);
    }

    private function addOrganisateur($userId , $data){
        $sql = "INSERT INTO club (name,date_de_creation) VALUES (?,NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$data['name']]);
        $club_id = $this->conn->lastInsertId();
        $query = "INSERT INTO organisateur (user_id,club_id) values (:user_id,:club_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id",$userId);
        $stmt->bindParam(":club_id",$club_id);
        $stmt->execute();
    }
    

}
?>