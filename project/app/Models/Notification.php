<?php
namespace App\Models;

class Notification {
    private $user_id;
    private $message;
    private $type;
    private $created_at;
    private $is_read;

    public function __construct($user_id, $message, $type, $created_at, $is_read) {
        $this->user_id = $user_id;
        $this->message = $message;
        $this->type = $type;
        $this->created_at = $created_at;
        $this->is_read = $is_read;
    }


    public function getUserId() {
        return $this->user_id;
    }

    public function getMessage() {
        return $this->message;
    }

    public function getType() {
        return $this->type;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function isRead() {
        return $this->is_read;
    }
}
