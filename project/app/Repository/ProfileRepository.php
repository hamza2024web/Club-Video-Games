<?php 
namespace App\Repository;

use Config\Database;

class ProfileRepository {
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function fillProfile($user_id,$name,$email,$phone,$gamer_tag,$bio){
        $query = "INSERT INTO profile (phone_number,gamer_tag,bio,profile_image)
        VALUES (:phone,:gamer_tag,:bio,:profile_image)";
    }

}
?>