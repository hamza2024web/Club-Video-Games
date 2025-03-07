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
        $query = "INSERT INTO genre (name , description , status) VALUES (:name,:description,:status)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name",$name);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":status",$status);
        $stmt->execute();
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
    public function deleteGenre($id){
        $query = "DELETE FROM genre WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id",$id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true; 
        } else {
            return false; 
        }
    }
    public function UpdateGenre($id,$name,$description,$status){
        $query = "UPDATE genre SET name = :name, description = :description, status = :status  where id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name",$name);
        $stmt->bindParam(":description",$description);
        $stmt->bindParam(":status",$status);
        $stmt->bindParam(":id",$id);
        $stmt->execute();
        $newGenre = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$newGenre){
            return null;
        } else {
            return new Genre($newGenre["id"] , $newGenre["name"],$newGenre["description"],$newGenre["status"]);
        }
    }
}


?>