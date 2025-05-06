<?php
namespace App\Models;

class TransactionLog {
    private $member_id;
    private $event_id;
    private $amount;
    private $old_balance;
    private $new_balance;
    private $transaction_type;
    private $created_by;
    private $created_at;

    public function __construct($member_id, $event_id, $amount, $old_balance, $new_balance, $transaction_type, $created_by, $created_at) {
        $this->member_id = $member_id;
        $this->event_id = $event_id;
        $this->amount = $amount;
        $this->old_balance = $old_balance;
        $this->new_balance = $new_balance;
        $this->transaction_type = $transaction_type;
        $this->created_by = $created_by;
        $this->created_at = $created_at;
    }

    public function getMemberId() {
        return $this->member_id;
    }

    public function getEventId() {
        return $this->event_id;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getOldBalance() {
        return $this->old_balance;
    }

    public function getNewBalance() {
        return $this->new_balance;
    }

    public function getTransactionType() {
        return $this->transaction_type;
    }

    public function getCreatedBy() {
        return $this->created_by;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }
}