<?php
namespace App\Services;

use App\Repository\PaymentRepository;
use App\Repository\TournamentsRepository;
use Exception;

class TournamentsServices {
    protected $TournamentsRepository;
    protected $PaymentServices;
    protected $PaymentRepository;
    public function __construct()
    {
        $this->TournamentsRepository = new TournamentsRepository();
        $this->PaymentServices = new PayementServices();
        $this->PaymentRepository = new PaymentRepository();
    }

    public function getAllTournoi(){
        $tournois = $this->TournamentsRepository->getAllTournois();
        return $tournois;
    }
    public function Inscription($user_id, $tournoi_id, $frais_inscription) {
        $currentSoldeData = $this->PaymentServices->validatePrice($user_id);
        $currentSolde = (float)$currentSoldeData;
        $price = (float)$frais_inscription;
        if ($price <= $currentSolde) {
            try {
                $inscription = $this->TournamentsRepository->Inscription_Tournoi($user_id, $tournoi_id,$price);
                if($inscription) {
                    $newSolde = $currentSolde - $price;
                    $newCompteSolde = $this->PaymentRepository->updateUserSolde($user_id, $newSolde);
                    return $inscription;
                } else {
                    return false;
                }
            } catch (Exception $e) {
                error_log("Inscription error :" . $e->getMessage());
                return false;
            }
        } else {
            return false;
        }
        
    }
    public function getInscriptionTournoi($user_id){
        $inscriptions = $this->TournamentsRepository->getTournoiInscri($user_id);
        return $inscriptions;
    }
}
?>