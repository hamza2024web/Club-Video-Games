<?php
namespace App\Models;

class Notification {
    private $order_id;

    public function __construct($order_id) {
        $this->order_id = $order_id;
    }


    public function getorder_id() {
        return $this->order_id;
    }

}
