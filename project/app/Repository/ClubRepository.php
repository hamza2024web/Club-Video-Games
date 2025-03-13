<?php
namespace App\Repository;

use Config\Database;

class ClubRepository {
    private $conn;
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    
}

?>