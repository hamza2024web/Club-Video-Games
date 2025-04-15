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

    public function TatalMembers(){
        $activeMembers = $this->StaticAdminRepository->TatalActiveMembers();
        return $activeMembers;
    }

    public function TotalSessions(){
        $active_session = $this->StaticAdminRepository->CountActiveSession();
        return $active_session;
    }

    public function pendingSession(){
        $pending = $this->StaticAdminRepository->countPendingApprovale();
        return $pending;
    }
}
?>