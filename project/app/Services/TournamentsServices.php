<?php
namespace App\Services;

use App\Repository\TournamentsRepository;
use Exception;

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
    public function Inscription($user_id,$tournoi_id,$frais_inscription){
        $currentSoldeData = $this->validatePrice($user_id);
        $currentSolde = (float)$currentSoldeData;
        $price = (float)$frais_inscription;
        if ($price <= $currentSolde){
            try{
                $inscription = $this->TournamentsRepository->Inscription_Tournoi($user_id,$tournoi_id);
                if($inscription){
                    $newSolde = $currentSolde - $price;
                    $newCompteSolde = $this->PaymentRepository->updateUserSolde($user_id,$newSolde);
                    return $inscription;
                } else {
                    return false;
                }
            } catch (Exception $e){
                error_log("Inscription error :" .$e->getMessage());
                return false;
            }
        } else {
            return false;
        }
        $inscription = $this->TournamentsRepository->Inscription_Tournoi($user_id,$tournoi_id);
        return $inscription;
    }
    public function getInscriptionTournoi($user_id){
        $inscriptions = $this->TournamentsRepository->getTournoiInscri($user_id);
        return $inscriptions;
    }
}
?>