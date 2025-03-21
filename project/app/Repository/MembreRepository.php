<?php
namespace App\Repository;
use Config\Database;
use PDO;
use PDOException;

class MembreRepository {
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getProifle($user_id){
        try{
            $sql = "SELECT users.name , users.email , membre.date_naissance,membre.cover_photo,membre.profile_photo,membre.tag_name,membre.location,membre.about_me,membre.discord,membre.instagram,membre.youtube,membre.twitch  from membre 
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
    public function updateProfile($user_id,$username,$email,$tag_name,$location,$about,$discord,$instagram,$youtube,$twitch,$covre_photo,$profile_photo){
        try {
            $sqlUsers = "UPDATE users SET name=:name , email=:email WHERE id = :user_id";
            $stmt = $this->conn->prepare($sqlUsers);
            $stmt->bindParam(":user_id",$user_id);
            $stmt->execute();
        } catch (PDOException $e){
            echo "Error updating Profile Membre: " . $e->getMessage();
            return false;
        }
    }
}
?>