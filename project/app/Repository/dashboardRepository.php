<?php
namespace App\Repository;

use Config\Database;
use App\Models\Genre;
use PDO;
class dashboardRepository {
    private $conn ;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAllGenre(){
        $query = "SELECT * FROM genre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $genreData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $genreData;
    }

    public function setgenre($name , $description ,$status){
        $query = "INSERT INTO genre (name , description , status) VALUES (?,?,?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name",$name);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":status",$status);
        $stmt->execute([$name,$description,$status]);
        $genreId = $this->conn->lastInsertId();

        $stmt = $this->conn->prepare("SELECT * FROM genre WHERE id = ?");
        $stmt->execute([$genreId]);
        $genreData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$genreData){
            return null;
        } else {
            return new Genre($genreData["id"] , $genreData["name"], $genreData["description"] ,$genreData["status"]);
        }
    }  
}


?>