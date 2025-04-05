<?php
namespace App\Services;
use App\Repository\CompteRepository;

class CompteServices {
    protected $CompteRepository;

    public function __construct()
    {
        $this->CompteRepository = new CompteRepository();
    }
    public function getSolde($user_id){
        $getamount = $this->CompteRepository->getSold($user_id);
    }
    public function setSolde($user_id,$montant){
        $saveSolde = $this->CompteRepository->rechargerCompte($user_id,$montant);
        return $saveSolde;
    }
}
?>