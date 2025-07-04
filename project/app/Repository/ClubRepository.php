<?php
namespace App\Repository;

use Config\Database;
use PDO;

class ClubRepository {
    private $conn;
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    public function getClubOrganisateur($user_id){
        $sqlClub = "SELECT club_id FROM organisateur WHERE user_id = :user_id";
        $stmtClub = $this->conn->prepare($sqlClub);
        $stmtClub->bindParam(":user_id",$user_id);
        $stmtClub->execute();
        $club_idd = $stmtClub->fetch(PDO::FETCH_ASSOC);
        $club_id = $club_idd["club_id"];
        $sql = "SELECT * from club WHERE id=:club_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam("club_id",$club_id);
        $stmt->execute();
        $club = $stmt->fetch(PDO::FETCH_ASSOC);
        return $club;
    }
    public function updateClub($user_id,$name,$email,$phone_club,$description,$logo){
        $query = "SELECT club_id FROM organisateur WHERE user_id=:user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id",$user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $club_id = $result['club_id'] ?? null;
        $sql = "UPDATE club SET name=:name , email=:email , phone_club=:phone_club , logo=:logo ,description=:description WHERE id=:club_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":name",$name);
        $stmt->bindParam(":email",$email);
        $stmt->bindParam(":phone_club",$phone_club);
        $stmt->bindParam(":logo",$logo);    
        $stmt->bindParam(":description",$description);
        $stmt->bindParam(":club_id",$club_id);
        return $stmt->execute();
    }
    
    public function getMembers($user_id){
        $query = "SELECT club_id FROM organisateur WHERE user_id=:user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id",$user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $club_id = $result['club_id'] ?? null;
        $sqlTournoi = "SELECT users.name , users.email ,inscription_tournoi.date_inscription , evenement.name as event_name, membre.profile_photo
        FROM inscription_tournoi
        INNER JOIN membre ON membre.id = inscription_tournoi.membre_id
        INNER JOIN users ON users.id = membre.user_id
        INNER JOIN tournoi ON tournoi.id = inscription_tournoi.tournoi_id
        INNER JOIN evenement ON evenement.id = tournoi.event_id
        INNER JOIN club ON club.id = evenement.club_id
        WHERE club.id = :club_id";
        $stmt = $this->conn->prepare($sqlTournoi);
        $stmt->bindParam(":club_id",$club_id);
        $stmt->execute();
        $tournois = $stmt->fetchAll();
        return $tournois;
    }
}

?>