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
    public function setMembreAndOrganisateur($role,$name,$email,$password,$naissance,$club){
        try{
            if ($role === 'membre'){
                $status = "Activation";
            } elseif ($role === 'organisateur'){
                $status = "Not Active";
            }
            $hashedPassword = password_hash($password , PASSWORD_DEFAULT);
            $query = "INSERT INTO users (`name` , email , `password` , `role` , `status`) 
            VALUES (?,?,?,?,?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$name , $email ,$hashedPassword ,$role , $status]);
            $userId = $this->conn->lastInsertId();
            if ($role === 'membre'){
                $this->addMembre($userId , ['date_naissance' => $naissance]);
            } elseif ($role === 'organisateur'){
                $this->addOrganisateur($userId , ['nam_club' => $club]);
            }
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            return new Users($userData['email'] , $userData['password'] ,$userData['role'],$userData['status']);
        } catch (\PDOException $e){
            error_log("Databse error:" .$e->getMessage());
            return null;
        }
    }

    private function addMembre($userId ,$data){
        $sql = "INSERT INTO membre (date_naissance ,user_id) VALUES (? , ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$data['date_naissance'] , $userId]);
    }

    private function addOrganisateur($userId , $data){
        $sql = "INSERT INTO organisateur (user_id ,nam_club) VALUES (? , ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId,$data['nam_club']]);
    }
    

}
?>