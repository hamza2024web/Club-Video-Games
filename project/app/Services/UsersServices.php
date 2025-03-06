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

    public function Status($id , $newStatus){
        $users = $this->Usersrepository->editStatusById($id , $newStatus);
        return $users;
    }
}

?>