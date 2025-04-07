<?php
namespace App\Services;

use App\Repository\TournamentsRepository;

class TournamentsServices extends PayementServices {
    protected $TournamentsRepository;
    public function __construct()
    {
        $this->TournamentsRepository = new TournamentsRepository();
    }

    public function getAllTournoi(){
        $tournois = $this->TournamentsRepository->getAllTournois();
        return $tournois;
    }
    public function Inscription($user_id,$tournoi_id){
        $currentSolde = $this->validatePrice($user_id);
        $inscription = $this->TournamentsRepository->Inscription_Tournoi($user_id,$tournoi_id);
        return $inscription;
    }
    public function getInscriptionTournoi($user_id){
        $inscriptions = $this->TournamentsRepository->getTournoiInscri($user_id);
        return $inscriptions;
    }
}
?>