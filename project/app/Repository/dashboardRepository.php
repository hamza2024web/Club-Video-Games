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

    public function getPlayers(){
        $sql = "SELECT users.name , membre.profile_photo ,transaction_log.amount FROM users
        INNER JOIN membre ON users.id = membre.user_id
        INNER JOIN transaction_log ON transaction_log.member_id = membre.id
        HAVING amount >= 10
        LIMIT 3 ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $friends = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $friends;
    }

    public function getAllEvents(){
        $sql = "SELECT users.name as organisateur, club.name as club_name, evenement.id , evenement.name , evenement.date_debut , evenement.date_fin , evenement.lieu , evenement.statut ,evenement.description,evenement.type_evenement,evenement.numbre_membre,evenement.registration_start,evenement.registration_end,evenement.event_time,evenement.event_photo,evenement.entry_fee,evenement.requirements,evenement.discord_url,evenement.twitch_url,GROUP_CONCAT(evenement_programme.timeline_time),GROUP_CONCAT(evenement_programme.timeline_title),GROUP_CONCAT(evenement_programme.timeline_desc)
        , count(inscription_evenement.membre_id) as number_participants  FROM evenement
        INNER JOIN club ON evenement.club_id = club.id
        INNER JOIN evenement_programme ON evenement_programme.event_id = evenement.id
        LEFT JOIN inscription_evenement ON inscription_evenement.evenement_id = evenement.id
        INNER JOIN organisateur ON organisateur.club_id = evenement.club_id
        INNER JOIN users ON users.id = organisateur.user_id
        GROUP BY evenement.id , users.name";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getAllTournoi(){
        $sql = "SELECT users.name AS organisateur ,club.name AS club_name , tournoi.id,tournoi.name,tournoi.date_de_debut,tournoi.date_de_fin,tournoi.numbre_membre,tournoi.statut,tournoi.regles,tournoi.description,jeux.nom_de_jeu
        ,tournoi.prix_total ,tournoi.date_ouverture_inscription,tournoi.date_cloture_inscription,tournoi.frais_inscription,tournoi.discord,tournoi.twitch,tournoi.image , COUNT(inscription_tournoi.membre_id) AS number_inscription FROM tournoi
        INNER JOIN jeux ON jeux.id = tournoi.jeu_id
        INNER JOIN evenement ON evenement.id = tournoi.event_id
        INNER JOIN club ON club.id = evenement.club_id
        LEFT JOIN inscription_tournoi ON inscription_tournoi.tournoi_id = tournoi.id
        INNER JOIN organisateur ON organisateur.club_id = evenement.club_id
        INNER JOIN users ON users.id = organisateur.user_id
        GROUP BY tournoi.id , users.name";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getAllGenre(){
        $query = "SELECT * FROM genre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $genreData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $genreData;
    }

    public function getAllGame(){
        $query = "SELECT jeux.id,jeux.nom_de_jeu,jeux.description,jeux.plateforme,jeux.date_de_sortie,jeux.developpeur,jeux.image,jeux.prix,jeux.status ,jeux.stock, COALESCE(GROUP_CONCAT(genre.name), 'No Genre') as genre FROM jeux 
        LEFT JOIN genre_jeux ON genre_jeux.jeux_id = jeux.id
        LEFT JOIN genre ON genre.id = genre_jeux.genre_id
        GROUP BY jeux.id";
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
    public function addGame($title, $plateform, $genre_id, $developer, $date_de_sortie, $description, $prix, $status, $image,$stock) {
        try {
            $this->conn->beginTransaction();
                $sql = "INSERT INTO jeux (nom_de_jeu, description, plateforme, date_de_sortie, developpeur, image, prix, status,stock) VALUES (:title, :description, :plateforme, :date_de_sortie, :developpeur, :image, :prix, :status,:stock)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":plateforme", $plateform);
            $stmt->bindParam(":date_de_sortie", $date_de_sortie);
            $stmt->bindParam(":developpeur", $developer);
            $stmt->bindParam(":image", $image);
            $stmt->bindParam(":prix", $prix);
            $stmt->bindParam(":status", $status);
            $stmt->bindParam(":stock",$stock);
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
    
    public function updateGame($gameId, $title, $genre_id, $plateform, $developer, $date_de_sortie, $description, $image, $prix, $status,$stock) {
        try {
            $this->conn->beginTransaction();
            $sql = "UPDATE jeux SET nom_de_jeu = :title, description = :description, plateforme = :plateform, date_de_sortie = :date_de_sortie, developpeur = :developer, image = :image, prix = :prix, status = :status ,stock = :stock WHERE id = :gameId";
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
            $stmt->bindParam(":stock",$stock);
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
            if ($genre_id === null || !is_array($genre_id)) {
                return true;
            }
    
            $stmt = $this->conn->prepare("DELETE FROM genre_jeux WHERE jeux_id = :gameId");
            $stmt->bindParam(":gameId", $gameId, PDO::PARAM_INT);
            $stmt->execute();
    
            if (!empty($genre_id)) {
                $sqlInsert = "INSERT INTO genre_jeux (jeux_id, genre_id) VALUES (:gameId, :genreId)";
                $stmtInsert = $this->conn->prepare($sqlInsert);
    
                foreach ($genre_id as $genre) {
                    $stmtInsert->bindParam(":gameId", $gameId, PDO::PARAM_INT);
                    $stmtInsert->bindParam(":genreId", $genre, PDO::PARAM_INT);
                    $stmtInsert->execute();
                }
            }
    
            return true;
        } catch (PDOException $e) {
            echo "Error updating game genres: " . $e->getMessage();
            return false;
        }
    }
    
    
    public function deleteGame($gameId){
        try {
            $this->conn->beginTransaction();
            $sql = "DELETE FROM genre_jeux WHERE jeux_id = :gameId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":gameId", $gameId, PDO::PARAM_INT);
            $stmt->execute();
            $sql = "DELETE FROM jeux WHERE id = :gameId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":gameId", $gameId, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $this->conn->commit();
                return true;
            } else {
                $this->conn->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            $this->conn->rollBack();
            echo "Error deleting game: " . $e->getMessage();
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
    
    public function getAllTransactions(){
        $sql = "SELECT users.name , evenement.name,transaction_log.amount,transaction_log.old_balance,transaction_log.new_balance,transaction_log.transaction_type FROM transaction_log
        INNER JOIN evenement ON transaction_log.event_id = evenement.id
        INNER JOIN membre ON membre.id = transaction_log.member_id
        INNER JOIN users ON users.id = membre.user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $transactions;
    }
}

?>