<?php
namespace App\Services;
use App\Repository\UsersRepositpry;

class UsersServices {
    protected $Usersrepository;

    public function __construct()
    {
        $this->Usersrepository = new UsersRepositpry();
    }

    public function getUsers(){
        $users = $this->Usersrepository->getAllUsers();
        return $users;
    }
}

?>