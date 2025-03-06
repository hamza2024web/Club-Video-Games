<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\UsersServices;

class AdminController extends BaseController { 
    protected $usersServices;
    public function __construct()
    {
        parent::__construct();
        $this->usersServices = new UsersServices();

    }

    public function dashboard (){
        $this->renderAdmin('dashboard');
    }

    public function Users(){
        $UsersStatus = $this->usersServices->getUsers();
        $this->renderAdmin('userList',['results' => $UsersStatus]);
        return $UsersStatus;
    }

    public function updateStatus(){
        $id = $_POST['id'];
        $newStatus = $_POST['status'];
        $UpdateStatus = $this->usersServices->Status($id , $newStatus);
        $status = $this->Users();
    }
}
?>