<?php
namespace App\Repository;
use Config\Database;
use App\Models\Users;
use PDO;

class UserRepository {
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function findUserByEmail($email){
        $query = "SELECT users.id ,users.email , users.password , users.role ,users.status
        FROM users WHERE users.email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email",$email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAllUsers(){
        $query = "SELECT users.id , users.name , users.email ,users.role ,users.status FROM users";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $userData;
    }
    public function editStatusById($id, $newstatus) {
        $query = "UPDATE users SET status = :newstatus WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":newstatus", $newstatus);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return true;
    }
    
}

?>