<?php
namespace App\Models;

class Compte {
    private $solde;
    private $last_updated;

    public function __construct($solde,$last_updated)
    {
        $this->solde = $solde;
        $this->last_updated = $last_updated;
    }

    public function getSolde(){
        return $this->solde;
    }

    public function getLastUpdated(){
        return $this->last_updated;
    }
}
?>