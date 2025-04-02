<?php
// app/Services/PayPalService.php

namespace App\Services;

use PaypalServerSdkLib\PaypalServerSdkClientBuilder;
use PaypalServerSdkLib\Authentication\ClientCredentialsAuthCredentialsBuilder;
use PaypalServerSdkLib\Environment;
use PaypalServerSdkLib\Models\Builders\OrderRequestBuilder;
use PaypalServerSdkLib\Models\Builders\PurchaseUnitRequestBuilder;
use PaypalServerSdkLib\Models\Builders\AmountWithBreakdownBuilder;
use PaypalServerSdkLib\Models\Builders\AmountBreakdownBuilder;
use PaypalServerSdkLib\Models\Builders\MoneyBuilder;
use PaypalServerSdkLib\Models\Builders\ItemBuilder;

class PayPalService {
    private $client;
    
    public function __construct() {
        $clientId = getenv("PAYPAL_CLIENT_ID");
        $clientSecret = getenv("PAYPAL_CLIENT_SECRET");
        
        $this->client = PaypalServerSdkClientBuilder::init()
            ->clientCredentialsAuthCredentials(
                ClientCredentialsAuthCredentialsBuilder::init(
                    $clientId,
                    $clientSecret
                )
            )
            ->environment(Environment::SANDBOX)
            ->build();
    }
    
    /**
     * Traite la réponse de l'API PayPal
     */
    private function handleResponse($response) {
        $jsonResponse = json_decode($response->getBody(), true);
        return [
            "jsonResponse" => $jsonResponse,
            "httpStatusCode" => $response->getStatusCode(),
        ];
    }
    
    /**
     * Crée une commande PayPal
     */
    public function createOrder($cart) {
        // Construisez vos données de commande en fonction de votre panier
        $items = [];
        $total = 0;
        
        foreach ($cart as $item) {
            $itemPrice = $item['price'];
            $quantity = $item['quantity'] ?? 1;
            $total += $itemPrice * $quantity;
            
            $items[] = ItemBuilder::init(
                $item['name'],
                MoneyBuilder::init("EUR", (string)$itemPrice)->build(),
                (string)$quantity
            )
                ->description($item['description'] ?? "")
                ->sku($item['id'])
                ->build();
        }
        
        $orderBody = [
            "body" => OrderRequestBuilder::init("CAPTURE", [
                PurchaseUnitRequestBuilder::init(
                    AmountWithBreakdownBuilder::init("EUR", (string)$total)
                        ->breakdown(
                            AmountBreakdownBuilder::init()
                                ->itemTotal(
                                    MoneyBuilder::init("EUR", (string)$total)->build()
                                )
                                ->build()
                        )
                        ->build()
                )
                    ->items($items)
                    ->build(),
            ])
            ->build(),
        ];
        
        $apiResponse = $this->client->getOrdersController()->createOrder($orderBody);
        
        return $this->handleResponse($apiResponse);
    }
    
    /**
     * Capture un paiement pour une commande
     */
    public function captureOrder($orderId) {
        $captureBody = [
            "id" => $orderId,
        ];
        
        $apiResponse = $this->client->getOrdersController()->captureOrder($captureBody);
        
        return $this->handleResponse($apiResponse);
    }
}