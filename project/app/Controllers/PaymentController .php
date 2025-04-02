<?php
// app/Controllers/PaymentController.php

namespace App\Controllers;

use App\Services\PayPalService;

class PaymentController {
    private $paypalService;
    
    public function __construct() {
        $this->paypalService = new PayPalService();
    }
    
    /**
     * Crée une commande PayPal
     */
    public function createOrder() {
        $data = json_decode(file_get_contents("php://input"), true);
        $cart = $data["cart"];
        
        header("Content-Type: application/json");
        
        try {
            $orderResponse = $this->paypalService->createOrder($cart);
            echo json_encode($orderResponse["jsonResponse"]);
        } catch (\Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
            http_response_code(500);
        }
    }
    
    /**
     * Capture un paiement
     */
    public function captureOrder($orderId) {
        header("Content-Type: application/json");
        
        try {
            $captureResponse = $this->paypalService->captureOrder($orderId);
            echo json_encode($captureResponse["jsonResponse"]);
        } catch (\Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
            http_response_code(500);
        }
    }
}