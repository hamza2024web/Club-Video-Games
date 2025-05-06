<?php
namespace App\Services;

use App\Repository\TournoiRepository;
use Exception;

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

    public function saveMatch($match){
        $match = $this->TournoiRepository->saveMatche($match);
        return $match;
    }

    public function getMatchesByTournament($tournoi_id){
        $matches = $this->TournoiRepository->getTournoiMatches($tournoi_id);
        return $matches;
    }

    public function setMatchResult($tournoi_id, $match_id, $participant1_id, $participant1_score, $participant2_id, $participant2_score) {
        try {
            $match = $this->TournoiRepository->updateMatchWithScores($tournoi_id, $match_id, $participant1_id, $participant1_score, $participant2_id, $participant2_score);
    
            if (isset($match['winner_id']) && $match['winner_id']) {
                $isComplete = $this->TournoiRepository->verifyTheTournoiIsCompleted($tournoi_id, $match);
                
                if ($isComplete === true) {
                    return false;
                } else {
                    $this->TournoiRepository->advanceWinnerToNextRound($tournoi_id, $match);
                    return true;
                }
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error updating match result: " . $e->getMessage());
            return false;
        }
    }
}
?>