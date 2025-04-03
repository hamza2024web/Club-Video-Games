<?php

namespace App\Controllers;
use App\Services\PayementServices;

class PaymentController {
    protected $PayementServices;

    public function __construct()
    {
        $this->PayementServices = new PayementServices();
    }

    public function payer(){
        $game_id = $_POST["games_id"];
        $order_id = $_POST["order_id"];

        $saveOrder = $this->PayementServices->saveOrder($game_id,$order_id);
    }
}