<?php
namespace App\Services;
use App\Repository\EventsMemberRepository;
use Exception;

class EventsMemberServices {
    protected $EventsMemberRepository;
    protected $PaymentRepository;

    public function __construct()
    {
        $this->EventsMemberRepository = new EventsMemberRepository();
    }

    public function getAllEvents(){
        $events = $this->EventsMemberRepository->getEvents();
        return $events;
    }

    public function inscriptionEvent($user_id,$event_id,$frais_inscription,$currentSoldeData){
        $currentSolde = (float)$currentSoldeData;
        $price = (float)$frais_inscription;
        if ($price <= $currentSolde){
            try{
                $inscription = $this->EventsMemberRepository->inscription_event($user_id,$event_id,$price);
                if ($inscription){
                    $newSolde = $currentSolde - $price;
                    $newCompteSolde = $this->PaymentRepository->updateUserSolde($user_id, $newSolde);
                    return $inscription;
                } else {
                    return false;
                }
            } catch (Exception $e){
                error_log("Inscription error :" .$e->getMessage());
                return false;
            }
        } else {
            return false ;
        }
    }
}
?>