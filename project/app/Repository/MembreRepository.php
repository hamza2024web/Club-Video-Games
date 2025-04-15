<?php
namespace App\Repository;
use Config\Database;
use PDO;
use PDOException;
use Exception;

class MembreRepository {
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getProfile($user_id){
        try{
            $sql = "SELECT users.name , users.email , membre.date_naissance ,membre.cover_photo,membre.profile_photo,membre.tag_name,membre.location,membre.about_me,membre.discord,membre.instagram,membre.youtube,membre.twitch  from membre 
            INNER JOIN users ON users.id = membre.user_id
            WHERE users.id = :user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":user_id",$user_id);
            $stmt->execute();
            $profile = $stmt->fetch(PDO::FETCH_ASSOC);
            return $profile;
        } catch(PDOException $e) {
            echo "Error fetch Profile Membre: " . $e->getMessage();
            return false;
        }
    }
    public function updateProfile($user_id,$username,$email,$tag_name,$location,$about,$discord,$instagram,$youtube,$twitch,$cover_photo,$profile_photo){
        try {
            $this->conn->beginTransaction();
            $sqlUsers = "UPDATE users SET name=:name , email=:email WHERE id = :user_id";
            $stmt = $this->conn->prepare($sqlUsers);
            $stmt->bindParam(":name",$username);
            $stmt->bindParam(":email",$email);
            $stmt->bindParam(":user_id",$user_id);
            $isInsertUser = $stmt->execute();
            if (!$isInsertUser){
                $this->conn->rollBack();
                throw new Exception("Failed To Update User Details.");
            }
            $sql = "UPDATE membre SET cover_photo=:cover_photo , profile_photo=:profile_photo , tag_name=:tag_name , location=:location , about_me=:about_me , discord=:discord,instagram=:instagram,youtube=:youtube,twitch=:twitch
            WHERE user_id=:user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":cover_photo",$cover_photo);
            $stmt->bindParam(":profile_photo",$profile_photo);
            $stmt->bindParam(":tag_name",$tag_name);
            $stmt->bindParam(":location",$location);
            $stmt->bindParam(":about_me",$about);
            $stmt->bindParam(":discord",$discord);
            $stmt->bindParam(":instagram",$instagram);
            $stmt->bindParam(":youtube",$youtube);
            $stmt->bindParam(":twitch",$twitch);
            $stmt->bindParam(":user_id",$user_id);
            $isProfileUpdated = $stmt->execute();
            if(!$isProfileUpdated){
                $this->conn->rollBack();
                throw new Exception("Failed To Update Profile Details.");
            }
            $this->conn->commit();
            return true;
        } catch (Exception  $e){
            error_log("Error updating Profile Membre: " . $e->getMessage());  
            return false;
        }
    }

    public function getAllNotification($user_id){
        $sql = "SELECT COUNT(notifications.user_id) as notification_number FROM notifications WHERE user_id = :user_id GROUP BY notifications.id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id",$user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}
?>