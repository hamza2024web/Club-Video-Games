<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\dashboardServices;
use App\Services\UsersServices;

class AdminController extends BaseController { 
    protected $usersServices;
    protected $adminServices;
    public function __construct()
    {
        parent::__construct();
        $this->usersServices = new UsersServices();
        $this->adminServices = new dashboardServices();
    }

    public function dashboard (){
        $this->renderAdmin('dashboard');
    }
    
    public function genre(){
        $genres = $this->adminServices->getGenre();
        $this->renderAdmin('genre',compact('genres'));
        return $genres;
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
    public function addGenre(){
        $name = $_POST["name"];
        $description = $_POST["description"];
        $status = $_POST["status"];
        $addGenre = $this->adminServices->addGenre($name , $description,$status);
        header("location: genre");
    }
    public function deleteGenre(){
        $id = $_POST["id"];
        $delete = $this->adminServices->delete($id);
        header("location: genre");
        return $delete;
    }
    public function updateGenre(){
        $id = $_POST["id"];
        $name = $_POST["name"];
        $description = $_POST["description"];
        $status = $_POST["status"];

        $editGenre = $this->adminServices->editGenre($id,$name,$description,$status);
        header("location: genre");
        return $editGenre;
    }
}
?>