<?php
namespace App\Repository;

use Config\Database;
use PDO;

class JeuxRepository {
    private $conn ;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    public function getGames($user_id){
        $sql = "SELECT jeux.id,jeux.nom_de_jeu,jeux.description,jeux.plateforme,jeux.date_de_sortie,jeux.developpeur,jeux.image,jeux.prix,jeux.status ,jeux.stock,COALESCE(GROUP_CONCAT(genre.name), 'No Genre') as genre FROM jeux 
        LEFT JOIN genre_jeux ON genre_jeux.jeux_id = jeux.id
        LEFT JOIN genre ON genre.id = genre_jeux.genre_id
        INNER JOIN orders ON orders.jeu_id = jeux.id
        WHERE orders.user_id = :user_id
        GROUP BY jeux.id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id",$user_id);
        $stmt->execute();
        $games = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $games;
    }
}

?>