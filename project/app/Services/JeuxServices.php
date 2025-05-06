<?php
namespace App\Services;
use App\Repository\JeuxRepository;


class JeuxServices {
    protected $JeuxRepository;

    public function __construct()
    {
        $this->JeuxRepository = new JeuxRepository();
    }
    public function getGame($user_id){
        $games = $this->JeuxRepository->getGames($user_id);
        return $games;
    }
}
?>