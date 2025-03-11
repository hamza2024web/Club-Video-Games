<?php 
namespace App\Repository;

use App\Models\Profile;
use App\Models\Users;
use Config\Database;
use PDO;

class ProfileRepository {
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getProfile($user_id){
        $sql = "SELECT users.name , users.email ,profile.phone_number,profile.gamer_tag,profile.bio,profile.profile_image
        FROM profile
        INNER JOIN users ON users.id = profile.user_id
        WHERE profile.user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id",$user_id);
        $stmt->execute();
        $profileUser = $stmt->fetch(PDO::FETCH_ASSOC);
        return $profileUser;
        }

    public function updateUser($user_id, $name, $email) {
        $sql = "UPDATE users SET name = :name, email = :email WHERE id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":user_id", $user_id);
        return $stmt->execute();
    }

    public function updateProfile($user_id, $phone, $gamer_tag, $bio, $profile_image) {
        $sql = "INSERT INTO profile (phone_number, gamer_tag, bio, profile_image, user_id)
        VALUES (:phone, :gamer_tag, :bio, :profile_image, :user_id)
        ON DUPLICATE KEY UPDATE 
        phone_number = VALUES(phone_number),
        gamer_tag = VALUES(gamer_tag),
        bio = VALUES(bio),
        profile_image = VALUES(profile_image)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":gamer_tag", $gamer_tag);
        $stmt->bindParam(":bio", $bio);
        $stmt->bindParam(":profile_image", $profile_image);
        $stmt->bindParam(":user_id", $user_id);

        return $stmt->execute();
    }
    public function getPassword($user_id){
        $sql = "SELECT password FROM users WHERE id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updatePassword($user_id, $hashed_password){
        $sql = "UPDATE users SET password = :correctPassword WHERE id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":correctPassword", $hashed_password);
        return $stmt->execute(); 
    }
    
}
?>