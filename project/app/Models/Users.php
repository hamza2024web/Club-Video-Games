<?php
namespace App\Classes;

class Users {
    private $email;
    private $password;
    private $role;
    private $status;

    public function __construct($email ,$password ,$role,$status)
    {
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->status=$status;
    }

    public function getRole(){
        return $this->role;
    }
    public function getStatus(){
        return $this->status;
    }
}
?>