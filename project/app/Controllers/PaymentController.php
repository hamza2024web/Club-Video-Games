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
        $price = $_POST["price"];
        $total = $_POST["total_amount"];

        $saveOrder = $this->PayementServices->saveOrder($user_id,$game_id,$order_id,$price,$total);
        $referer = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) : '/boutique';
        if ($saveOrder){
            header("location:" . $referer ."?paiment_réussite=1");
        } else {
            header("location:" .$referer ."?paiment_échouée=1");
        }
    }
}