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
        if ($UpdateStatus){
            header("location: /users");
        } else {
            echo "Failde To Update Status";
        }
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
    public function editGenre(){
        $id = $_POST["id"];
        $name = $_POST["name"];
        $description = $_POST["description"];
        $status = $_POST["status"];

        $editGenre = $this->adminServices->editGenre($id,$name,$description,$status);
        header("location: genre");
        return $editGenre;
    }
    public function game(){
        $games = $this->adminServices->getGame();
        $genres = $this->adminServices->getGenre();
        return $this->renderAdmin('game',compact('genres','games'));
    }
    public function addGame(){
        $title = $_POST["title"];
        $plateform = $_POST["platform"];
        $genre_id = $_POST["genreId"];
        $developer = $_POST["developer"];
        $date_de_sortie = $_POST["releaseYear"];
        $description = $_POST["description"];
        $prix = $_POST["prix"];
        $status = $_POST["status"];
        $image = "default.jpg";

        if (isset($_FILES['coverImage']) && $_FILES['coverImage']['error'] == 0) {
            $upload_dir = __DIR__ . '/../../public/uploads/';
            $file_name = time() . '_' . basename($_FILES['coverImage']['name']);
            $target_path = $upload_dir . $file_name;
    
            // Validate file type
            $file_type = mime_content_type($_FILES['coverImage']['tmp_name']);
            if (strpos($file_type, 'image') === false) {
                die("Invalid file type. Please upload an image.");
            }
    
            // Move file and update the logo variable
            if (move_uploaded_file($_FILES['coverImage']['tmp_name'], $target_path)) {
                $image = 'public/uploads/' . $file_name; // Save only the file path
            } else {
                die("Failed to upload image.");
            }
        }
        
        $saveGame = $this->adminServices->saveGame($title,$plateform,$genre_id,$developer,$date_de_sortie,$description,$prix,$status,$image);
        if ($saveGame) {
            header("Location: /Game?game_Inserted_successfully=1");
            exit();
        } else {
            echo "Failed to insert Game.";
        }
    }
    public function EditGame(){
        $gameId = $_POST["id"];
        $title = $_POST["title"];
        $genre_id = isset($_POST["genreId"]) ? (is_array($_POST["genreId"]) ? $_POST["genreId"] : [$_POST["genreId"]]) : [];
        $plateform = $_POST["platform"];
        $developer = $_POST["developer"];
        $date_de_sortie = $_POST["releaseYear"];
        $description = $_POST["description"];
        $prix = $_POST["prix"];
        $status = $_POST["status"];
        $currentImage = $this->adminServices->getImage($gameId);

        $image = ($currentImage && isset($currentImage['image'])) ? $currentImage['image'] : 'default.jpg';
        
        if (isset($_FILES['coverImage']) && $_FILES['coverImage']['error'] == 0) {
            $upload_dir = __DIR__ . '/../../public/uploads/';
            $file_name = time() . '_' . basename($_FILES['coverImage']['name']);
            $target_path = $upload_dir . $file_name;
    
            // Validate file type
            $file_type = mime_content_type($_FILES['coverImage']['tmp_name']);
            if (strpos($file_type, 'image') === false) {
                die("Invalid file type. Please upload an image.");
            }
    
            // Move file and update the logo variable
            if (move_uploaded_file($_FILES['coverImage']['tmp_name'], $target_path)) {
                $image = 'public/uploads/' . $file_name; // Save only the file path
            } else {
                die("Failed to upload image.");
            }
        }


        $updateGame = $this->adminServices->setGame($gameId,$title,$genre_id,$plateform,$developer,$date_de_sortie,$description,$image,$prix,$status);
        if ($updateGame) {
            header("Location: /Game?game_Updated_successfully=1");
            exit();
        } else {
            echo "Failed to update Game.";
        }
    }
    public function deleteGame(){
        $gameId = $_POST["id"];
        $result = $this->adminServices->GameDelete($gameId);
        if ($result) {
            header("Location: /Game?game_Deleted_successfully=1");
            exit();
        } else {
            echo "Failed to delete Game.";
        }
    }

}
?>