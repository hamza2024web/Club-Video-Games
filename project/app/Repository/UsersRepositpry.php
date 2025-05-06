<?php
namespace App\Repository;
use Config\Database;
use PDO;

class UsersRepositpry {
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAllUsers(){
        $query = "SELECT users.id, users.name,users.email,users.password,users.status,role.name AS role,profile.profile_image
        FROM users
        INNER JOIN role ON role.id = users.role_id
        LEFT JOIN profile ON profile.user_id = users.id  
        WHERE role.name = 'membre' 
        OR role.name = 'organisateur';
        ";
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