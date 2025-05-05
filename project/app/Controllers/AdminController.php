<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\dashboardServices;
use App\Services\StatistiqueAdminServices;
use App\Services\UsersServices;

class AdminController extends BaseController { 
    protected $usersServices;
    protected $adminServices;
    protected $StatistiqueAdminServices;
    public function __construct()
    {
        parent::__construct();
        $this->usersServices = new UsersServices();
        $this->adminServices = new dashboardServices();
        $this->StatistiqueAdminServices = new StatistiqueAdminServices();
    }

    public function dashboard (){
        $total_games = $this->StatistiqueGames();
        $active_member = $this->activeMember();
        $active_session = $this->activeSession();
        $pending_session = $this->pendingSession();
        $top_players = $this->adminServices->getTopPlayers();
        $events = $this->adminServices->getEvents();
        $tournaments = $this->adminServices->getTournaments();
        $transactions = $this->adminServices->getTransactions();
        return $this->renderAdmin('dashboard',compact('total_games','active_member','active_session','pending_session','top_players','events','tournaments','transactions'));
    }

    public function StatistiqueGames (){
        $games = $this->StatistiqueAdminServices->totalGames();
        return $games;
    }

    public function activeMember(){
        $members = $this->StatistiqueAdminServices->TatalMembers();
        return $members;
    }

    public function activeSession(){
        $sessions = $this->StatistiqueAdminServices->TotalSessions();
        return $sessions;
    }

    public function pendingSession (){
        $pending = $this->StatistiqueAdminServices->pendingSession();
        return $pending;
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
        $stock = $_POST["stock"];
        $image = "default.jpg";

        if (isset($_FILES['coverImage'])) {
            $image = $this->generateImage($_FILES['coverImage'], $image);
        }
        $saveGame = $this->adminServices->saveGame($title,$plateform,$genre_id,$developer,$date_de_sortie,$description,$prix,$status,$image,$stock);
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
        $stock = $_POST["stock"];
        $currentImage = $this->adminServices->getImage($gameId);

        $image = ($currentImage && isset($currentImage['image'])) ? $currentImage['image'] : 'default.jpg';
        
        if (isset($_FILES["coverImage"]) && $_FILES["coverImage"]["error"] == 0) {
            $image = $this->generateImage($_FILES["coverImage"], $image);
        }
    
        $updateGame = $this->adminServices->setGame($gameId,$title,$genre_id,$plateform,$developer,$date_de_sortie,$description,$image,$prix,$status,$stock);
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