<?php
namespace App\Services;
use App\Repository\PaymentRepository;


class PayementServices {
    protected $PaymentRepository;

    public function __construct()
    {
        $this->PaymentRepository = new PaymentRepository();
    }

    public function saveOrder($user_id,$game_id,$order_id){
        $savePaiment = $this->PaymentRepository->savePayement($user_id,$game_id,$order_id);
        return $savePaiment;
    }
    public function getUserPurchasedGames($user_id){
        return $this->PaymentRepository->getUserPurchasedGames($user_id);
    }
}

?>