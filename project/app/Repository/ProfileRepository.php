<?php 
namespace App\Repository;

use App\Models\Profile;
use Config\Database;
use PDO;

class ProfileRepository {
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
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
    
}
?>