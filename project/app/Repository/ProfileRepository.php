<?php 
namespace App\Repository;

use Config\Database;
use PDO;

class ProfileRepository {
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function fillProfile($user_id,$name,$email,$phone,$gamer_tag,$profile_image,$bio){
        $sql = "UPDATE TABLE users SET name=:name , email:email WHERE id=:user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":name",$name);
        $stmt->bindParam(":email",$email);
        $stmt->execute([$name,$email]);

        $query = "INSERT INTO profile (phone_number,gamer_tag,bio,profile_image,user_id)
        VALUES (:phone,:gamer_tag,:bio,:profile_image,:user_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":phone",$phone);
        $stmt->bindParam(":gamer_tag",$gamer_tag);
        $stmt->bindParam(":bio",$bio);
        $stmt->bindParam("profile_image",$profile_image);
        $stmt->bindParam(":user_id",$user_id);
        $stmt->execute();
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$profile){
            return false;
        } else {
            return true;
        }
    }
}
?>