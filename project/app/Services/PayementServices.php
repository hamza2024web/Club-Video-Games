<?php
namespace App\Services;
use App\Repository\PaymentRepository;
use Exception;

class PayementServices {
    protected $PaymentRepository;

    public function __construct()
    {
        $this->PaymentRepository = new PaymentRepository();
    }

    public function saveOrder($user_id, $game_id, $order_id, $price, $total) {
        $currentSoldeData = $this->validatePrice($user_id);
        if (!$currentSoldeData || !isset($currentSoldeData)) {
            return false; 
        }
        $currentSolde = (float)$currentSoldeData;
        $total = (float)$total;

        if ($total <= $currentSolde) {            
            try {
                $savePaiment = $this->PaymentRepository->savePayement($user_id, $game_id, $order_id, $total);
                if ($savePaiment) {
                    $newSolde = $currentSolde - $total;
                    $newCompteSolde = $this->PaymentRepository->updateUserSolde($user_id, $newSolde);
                    return $savePaiment;
                } else {
                    return false;
                }
            } catch (Exception $e) {
                error_log("Order error: " . $e->getMessage());
                return false;
            }
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