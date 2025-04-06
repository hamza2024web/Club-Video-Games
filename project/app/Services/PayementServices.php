<?php
namespace App\Services;
use App\Repository\PaymentRepository;


class PayementServices {
    protected $PaymentRepository;

    public function __construct()
    {
        $this->PaymentRepository = new PaymentRepository();
    }

    public function saveOrder($user_id,$game_id,$order_id,$price,$total){
        $currentsolde = $this->validatePrice($user_id);
        if ($total <= $currentsolde){
            $savePaiment = $this->PaymentRepository->savePayement($user_id,$game_id,$order_id,$price);
            return $savePaiment;
        } else {
            return false;
        }

    }
    public function getUserPurchasedGames($user_id){
        return $this->PaymentRepository->getUserPurchasedGames($user_id);
    }
    public function validatePrice($user_id){
        $currentPrice = $this->PaymentRepository->GetSolde($user_id);
        return $currentPrice;
    }

}

?>