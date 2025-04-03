<?php

namespace App\Controllers;
use App\Services\PayementServices;
session_start();

class PaymentController {
    protected $PayementServices;

    public function __construct()
    {
        $this->PayementServices = new PayementServices();
    }

    public function payer(){
        $user_id = $_SESSION["user_id"];
        $game_id = $_POST["games_id"];
        $order_id = $_POST["order_id"];

        $saveOrder = $this->PayementServices->saveOrder($user_id,$game_id,$order_id);

        if ($saveOrder){
            header("location: /boutique?paiment_réussite=1");
        } else {
            header("location: /boutique?paiment_échouée=1");
        }
    }
}