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

    public function members_actif(){
        $members = $this->StaticAdminRepository->Count_Partcipants();
        return $members;
    }

    public function evenements(){
        $evenements = $this->StaticAdminRepository->Count_Events();
        return $evenements;
    }

    public function GamesPurchase($user_id){
        $gamesPurchace = $this->StaticAdminRepository->Count_games($user_id);
        return $gamesPurchace;
    }
}
?>