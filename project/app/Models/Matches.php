<?php
namespace App\Models;

class Matches {
    private $tournoi_id;
    private $round;
    private $match_number;
    private $participant1_id;
    private $participant1_name;
    private $participant2_id;
    private $participant2_name;
    private $score_participant1;
    private $score_participant2;
    private $winner_id;
    private $scheduled_date;
    private $status;

    public function __construct($tournoi_id, $round, $match_number, $participant1_id, $participant1_name, $participant2_id, $participant2_name, $score_participant1, $score_participant2, $winner_id, $scheduled_date, $status) {
        $this->tournoi_id = $tournoi_id;
        $this->round = $round;
        $this->match_number = $match_number;
        $this->participant1_id = $participant1_id;
        $this->participant1_name = $participant1_name;
        $this->participant2_id = $participant2_id;
        $this->participant2_name = $participant2_name;
        $this->score_participant1 = $score_participant1;
        $this->score_participant2 = $score_participant2;
        $this->winner_id = $winner_id;
        $this->scheduled_date = $scheduled_date;
        $this->status = $status;
    }


    public function getTournoiId() {
        return $this->tournoi_id;
    }

    public function getRound() {
        return $this->round;
    }

    public function getMatchNumber() {
        return $this->match_number;
    }

    public function getParticipant1Id() {
        return $this->participant1_id;
    }

    public function getParticipant1Name() {
        return $this->participant1_name;
    }

    public function getParticipant2Id() {
        return $this->participant2_id;
    }

    public function getParticipant2Name() {
        return $this->participant2_name;
    }

    public function getScoreParticipant1() {
        return $this->score_participant1;
    }

    public function getScoreParticipant2() {
        return $this->score_participant2;
    }

    public function getWinnerId() {
        return $this->winner_id;
    }

    public function getScheduledDate() {
        return $this->scheduled_date;
    }

    public function getStatus() {
        return $this->status;
    }
}
