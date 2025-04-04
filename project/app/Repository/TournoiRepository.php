<?php
namespace App\Repository;
use Config\Database;
use PDO;

class TournoiRepository {
    private $conn ;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    public function addTounroi($user_id,$name,$start_date,$end_date,$max_participants,$status,$rules,$game,$format,$description,$prix_total,$prize_first,$prize_second,$prize_third,$registration_start,$registration_end,$registration_fee,$discord_url,$stream_url,$tournament_photo){
        $query = "SELECT club_id FROM organisateur WHERE user_id=:user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id",$user_id);
        $stmt->execute();
        $club_idd = $stmt->fetch(PDO::FETCH_ASSOC);
        $club_id = $club_idd["club_id"];
        $sqlEvent = "INSERT INTO evenement (name,date_debut,date_fin,description,type_evenement,statut,numbre_membre,club_id) VALUES
        (:name,:date_debut,:date_fin,:description,:type_evenement,:statut,:numbre_membre,:club_id)";
        $stmt = $this->conn->prepare($sqlEvent);
        $stmt->bindParam(":name",$name);
        $stmt->bindPara(":date_debut",$start_date);
        $stmt->bindParam(":date_fin",$end_date);
        $stmt->bindParam(":description",$description);
        $stmt->bindParam(":type_evenement","Tournament");
        $stmt->bindParam(":statut",$status);
        $stmt->bindParam(":numbre_membre",$max_participants);
        $stmt->bindParam(":club_id",$club_id);
        $stmt->execute();
        $event_id = $this->conn->lastInsertId();
        $sql = "INSERT INTO tournoi (name,date_de_debut,date_de_fin,numbre_membre,statut,regles,jeu_id,event_id,format_tournoi,description,prix_total,premier_place,deuxieme_place,troisieme_place,date_ouverture_inscription,date_cloture_inscription,frais_inscription,discord,twitch,image) VALUES
        (:name,:date_de_debut,:date_de_fin,:numbre_membre,:statut,:regles,:jeu_id,:event_id,:format_tournoi,:description,:prix_total,:premier_place,:deuxieme_place,:troisieme_place,:date_ouverture_inscription,:date_cloture_inscription,:frais_inscription,:discord,:twitch,:image)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":name",$name);
        $stmt->bindParam(":date_de_debut",$start_date);
        $stmt->bindParam(":date_de_fin",$end_date);
        $stmt->bindParam(":numbre_membre",$max_participants);
        $stmt->bindParam(":statut",$status);
        $stmt->bindParam(":regles",$rules);
        $stmt->bindParam(":jeu_id",$game);
        $stmt->bindParam(":event_id",$event_id);
        $stmt->bindParam(":format_tournoi",$format);
        $stmt->bindParam(":description",$description);
        $stmt->bindParam(":prix_total",$prix_total);
        $stmt->bindParam(":premier_place",$prize_first);
        $stmt->bindParam(":deuxieme_place",$prize_second);
        $stmt->bindParam(":troisieme_place",$prize_third);
        $stmt->bindParam(":date_ouverture_inscription",$registration_start);
        $stmt->bindParam(":date_cloture_inscription",$registration_end);
        $stmt->bindParam(":frais_inscription",$registration_fee);
        $stmt->bindParam(":discord",$discord_url);
        $stmt->bindParam(":twitch",$stream_url);
        $stmt->bindParam(":image",$tournament_photo);
    }
}
?>