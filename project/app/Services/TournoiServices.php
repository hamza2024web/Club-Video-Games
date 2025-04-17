<?php
namespace App\Services;

use App\Repository\TournoiRepository;

class TournoiServices {
    protected $TournoiRepository;

    public function __construct()
    {
        $this->TournoiRepository = new TournoiRepository();
    }

    public function getTournoi($user_id){
        $tournoi = $this->TournoiRepository->getTournoiInformations($user_id);
        return $tournoi;
    }

    public function setTournoi($user_id,$name,$start_date,$end_date,$max_participants,$status,$rules,$game,$format,$description,$prix_total,$prize_first,$prize_second,$prize_third,$registration_start,$registration_end,$registration_fee,$discord_url,$stream_url,$tournament_photo){
        $putTournoi = $this->TournoiRepository->addTounroi($user_id,$name,$start_date,$end_date,$max_participants,$status,$rules,$game,$format,$description,$prix_total,$prize_first,$prize_second,$prize_third,$registration_start,$registration_end,$registration_fee,$discord_url,$stream_url,$tournament_photo);
        return $putTournoi;
    }

    public function getTournoiById($tournoi_id){
        $tournoi = $this->TournoiRepository->GetTournoi($tournoi_id);
        return $tournoi;
    }

    public function getTournamentParticipants($tournoi_id){
        $participants = $this->TournoiRepository->getParticipants($tournoi_id);
        return $participants;
    }

}
?>