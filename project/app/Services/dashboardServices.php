<?php 
namespace App\Services;

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

    public function addGenre ($name , $description){
        $genre = $this->dashboardRepository->setgenre($name , $description);
        return $genre;
    }
}

?>