<?php
namespace App\Repository;
use Config\Database;
use App\Models\Users;
use PDO;

class LoginRepository {
    private $conn;

    public function __construct()
    {   
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function findUserByEmail($email){
        $query = "SELECT users.id, users.email, users.password, role.name AS role, users.status
        FROM users
        INNER JOIN role ON role.id = users.role_id
        WHERE users.email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email",$email);  
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
}

?>