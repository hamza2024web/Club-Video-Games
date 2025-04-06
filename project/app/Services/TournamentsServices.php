<?php
namespace App\Services;

use App\Repository\TournamentsRepository;

class TournamentsServices {
    protected $TournamentsRepository;
    public function __construct()
    {
        $this->TournamentsRepository = new TournamentsRepository();
    }

    public function getAllTournoi(){
        $tournois = $this->TournamentsRepository->getAllTournois();
        return $tournois;
    }
}
?>