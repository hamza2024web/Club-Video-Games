<?php
namespace App\Repository;

use Config\Database;
use App\Models\Genre;
use Exception;
use PDO;
use PDOException;

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

    public function getAllGame(){
        $query = "SELECT jeux.id,jeux.nom_de_jeu,jeux.description,jeux.plateforme,jeux.date_de_sortie,jeux.developpeur,jeux.image,jeux.prix,jeux.status , genre.name as genre FROM jeux 
        INNER JOIN genre_jeux ON genre_jeux.jeux_id = jeux.id
        INNER JOIN genre ON genre.id = genre_jeux.genre_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $games = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $games;
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
    public function addGame($title, $plateform, $genre_id, $developer, $date_de_sortie, $description, $prix, $status, $image) {
        try {
            $this->conn->beginTransaction();
                $sql = "INSERT INTO jeux (nom_de_jeu, description, plateforme, date_de_sortie, developpeur, image, prix, status) VALUES (:title, :description, :plateforme, :date_de_sortie, :developpeur, :image, :prix, :status)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":plateforme", $plateform);
            $stmt->bindParam(":date_de_sortie", $date_de_sortie);
            $stmt->bindParam(":developpeur", $developer);
            $stmt->bindParam(":image", $image);
            $stmt->bindParam(":prix", $prix);
            $stmt->bindParam(":status", $status);
            $isGameInserted = $stmt->execute();
            $gameId = $this->conn->lastInsertId();
            if (!$isGameInserted || !$gameId) {
                throw new Exception("Failed to insert game. Game ID is null.");
            }
            $attachToGame = $this->attachGameToGenre($gameId, $genre_id);
            if (!$attachToGame) {
                throw new Exception("Failed to attach genres.");
            }
            $this->conn->commit();
            return $gameId;
    
        } catch (PDOException $e) {
            $this->conn->rollBack();
            echo "Error adding jeux: " . $e->getMessage();
            return null;
        }
    }
    private function attachGameToGenre($gameId,$genre_id){
        try {
            $sql = "INSERT INTO genre_jeux (jeux_id,genre_id) VALUES (:jeux_id , :genre_id)";
            $stmt = $this->conn->prepare($sql);
            foreach ($genre_id as $genre){
                $stmt->bindParam(":jeux_id",$gameId);
                $stmt->bindParam(":genre_id",$genre);
                $stmt->execute();
            }
            return $gameId;
        } catch (PDOException $e){
            echo "Error attaching genre to jeu:" . $e->getMessage();
            return null;
        }
    }
    
    public function updateGame($gameId, $title, $genre_id, $plateform, $developer, $date_de_sortie, $description, $image, $prix, $status) {
        try {
            $this->conn->beginTransaction();
            $sql = "UPDATE jeux SET nom_de_jeu = :title, description = :description, plateforme = :plateform, date_de_sortie = :date_de_sortie, developpeur = :developer, image = :image, prix = :prix, status = :status WHERE id = :gameId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":plateform", $plateform);
            $stmt->bindParam(":date_de_sortie", $date_de_sortie);
            $stmt->bindParam(":developer", $developer);
            $stmt->bindParam(":image", $image);
            $stmt->bindParam(":prix", $prix);
            $stmt->bindParam(":status", $status);
            $stmt->bindParam(":gameId", $gameId);
            $isGameUpdated = $stmt->execute();
            if (!$isGameUpdated) {
                $this->conn->rollBack();
                throw new Exception("Failed To Update Game Details.");
            }
            $sql = "DELETE FROM genre_jeux WHERE jeux_id = :gameId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":gameId", $gameId);
            $stmt->execute();
            if (!$this->UpdateGameToGenre($gameId,$genre_id)){
                $this->conn->rollBack();
                throw new Exception("Failed To update Game Genre.");
            }
            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            echo "Error updating Game: " . $e->getMessage();
            return false;
        }
    }
    
    private function UpdateGameToGenre($gameId, $genre_id) {
        try {
            if (!is_array($genre_id) || empty($genre_id)) {
                throw new Exception("Genre ID must be a non-empty array.");
            }
            $existingGenres = [];
            $stmt = $this->conn->prepare("SELECT genre_id FROM genre_jeux WHERE jeux_id = :gameId");
            $stmt->bindParam(":gameId", $gameId);
            $stmt->execute();
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $existingGenres[] = $row['genre_id'];
            }
            $sqlInsert = "INSERT INTO genre_jeux (jeux_id, genre_id) VALUES (:gameId, :genreId)";
            $stmtInsert = $this->conn->prepare($sqlInsert);
            foreach ($genre_id as $genre) {
                if (!in_array($genre, $existingGenres)) { 
                    $stmtInsert->bindParam(":gameId", $gameId, PDO::PARAM_INT);
                    $stmtInsert->bindParam(":genreId", $genre, PDO::PARAM_INT);
                    $stmtInsert->execute();
                }
            }
            return true;
        } catch (PDOException $e) {
            echo "Error attaching genre to game: " . $e->getMessage();
            return false;
        }
    }

    public function getGameImage($gameId) {
        $sql = "SELECT image FROM jeux WHERE id = :gameId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":gameId", $gameId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}

?>