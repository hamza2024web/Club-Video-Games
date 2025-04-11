<?php
namespace App\Repository;

use Config\Database;

class EvenementRepository {
    private $conn;
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    public function addEvent($user_id,$name_event,$registration_start,$registration_end,$type_event,$status,$location,$event_date,$event_time,$event_photo,$max_participants,$entry_fee,$description,$requirements,$timeline_time,$timeline_title,$timeline_desc,$discord_url,$twitch_url){

    }
}
?>