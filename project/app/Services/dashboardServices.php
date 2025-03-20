<?php 
namespace App\Services;
use App\Models\Jeu;

use App\Repository\dashboardRepository;

class dashboardServices {
    protected $dashboardRepository;

    public function __construct()
    {
        $this->dashboardRepository = new dashboardRepository();
    }

    public function getGenre(){
        $genre = $this->dashboardRepository->getAllGenre();
        return $genre;
    }

    public function addGenre ($name , $description,$status){
        $genre = $this->dashboardRepository->setgenre($name , $description,$status);
        return $genre;
    }
    public function delete($id){
        $delete = $this->dashboardRepository->deleteGenre($id);
        return $delete;
    }
    public function editGenre($id,$name,$description,$status){
        $newGenre = $this->dashboardRepository->UpdateGenre($id,$name,$description,$status);
        return $newGenre;
    }
    public function saveGame($title,$plateform,$genre_id,$developer,$date_de_sortie,$description,$prix,$status,$image,$stock){
        $saveGame = $this->dashboardRepository->addGame($title,$plateform,$genre_id,$developer,$date_de_sortie,$description,$prix,$status,$image,$stock);
        return $saveGame;
    }
    public function getGame(){
        $games = $this->dashboardRepository->getAllGame();
        return $games;
    }
    public function setGame($gameId,$title,$genre_id,$plateform,$developer,$date_de_sortie,$description,$image,$prix,$status,$stock){
        $newGame = $this->dashboardRepository->updateGame($gameId,$title,$genre_id,$plateform,$developer,$date_de_sortie,$description,$image,$prix,$status,$stock);
        return $newGame;
    }
    public function getImage($gameId){
        $image = $this->dashboardRepository->getGameImage($gameId);
        return $image;
    }
    public function GameDelete($gameId){
        $result = $this->dashboardRepository->deleteGame($gameId);
        return $result;
    }

}

?>