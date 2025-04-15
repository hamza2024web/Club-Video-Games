<?php 
namespace App\Services;

use App\Repository\StaticAdminRepository;

class StatistiqueAdminServices {
    protected $StaticAdminRepository;

    public function __construct()
    {
        $this->StaticAdminRepository = new StaticAdminRepository();
    }

    public function totalGames(){
        $Games = $this->StaticAdminRepository->CountGames();
        return $Games;
    }
}
?>