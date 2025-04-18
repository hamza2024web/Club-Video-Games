<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\JeuxServices;
use App\Services\MembreServices;
use App\Services\ProfileServices;
use App\Services\TournoiServices;

session_start();

class TournoiController extends BaseController{
    protected $TournoiServices;
    protected $ProfileServices;
    protected $JeuxServices;
    protected $MembreServices;
    
    public function __construct()
    {
        parent::__construct();
        $this->ProfileServices = new ProfileServices();
        $this->JeuxServices = new JeuxServices();
        $this->TournoiServices = new TournoiServices();
        $this->MembreServices = new MembreServices();
    }

    public function index(){
        $user_id = $_SESSION["user_id"];
        $my_solde = $this->solde($user_id);
        $profile = $this->ProfileServices->getProfileUser($user_id);
        $jeux = $this->JeuxServices->getGame($user_id);
        $tournois = $this->TournoiServices->getTournoi($user_id);
        $notifications = $this->MembreServices->CountNotifications($user_id);
        $notificationsContent = $this->MembreServices->GetAllNotifications($user_id);
        return $this->renderOrg('tournoi',compact('profile','jeux','tournois','my_solde','notifications','notificationsContent'));
    }

    public function addTournoi(){
        $user_id = $_SESSION["user_id"];
        $name = $_POST["name"];
        $game = $_POST["game"];
        $format = $_POST["format"];
        $start_date = $_POST["start_date"];
        $end_date = $_POST["end_date"];
        $max_participants = $_POST["max_participants"];
        $prix_total = $_POST["prix_total"];
        $prize_first = $_POST["prize_first"];
        $prize_second = $_POST["prize_second"];
        $prize_third = $_POST["prize_third"];
        $registration_start = $_POST["registration_start"];
        $registration_end = $_POST["registration_end"];
        $registration_fee = $_POST["registration_fee"];
        $description = $_POST["description"];
        $rules = $_POST["rules"];
        $stream_url = $_POST["stream_url"];
        $discord_url = $_POST["discord_url"];
        $currentImage = 'public/uploads/default.jpg';
        $image = $_FILES["tournament_photo"] ?? null ;
        
        $tournament_photo = $this->generateImage($image,$currentImage);
        $currentDate = date('Y-m-d');
        
        // Logique corrigée pour définir le statut
        if ($currentDate < $registration_start) {
            $status = 'Pending';
        } elseif ($currentDate <= $registration_end) {
            $status = 'Open';
        } else {
            
            $status = 'Cancelled'; 
        }
        
        $saveTournoi = $this->TournoiServices->setTournoi($user_id,$name,$start_date,$end_date,$max_participants,$status,$rules,$game,$format,$description,$prix_total,$prize_first,$prize_second,$prize_third,$registration_start,$registration_end,$registration_fee,$discord_url,$stream_url,$tournament_photo);
    
        if ($saveTournoi){
            header("location: /tournoi?Tournoi Created successfully!=1");
        } else {
            header("location: /tournoi?Creation de votre tournoi echouée=1");
        }
    }

    public function showTournamentBracket(){
        $tournoi_id = $_POST["tournoi_id"];

        $tournoi = $this->TournoiServices->getTournoiById($tournoi_id);

        $participants = $this->TournoiServices->getTournamentParticipants($tournoi_id);
        
        $existingMatches  = $this->TournoiServices->getMatchesByTournament($tournoi_id);
        
        $matches = [];
        
        if(empty($existingMatches)){
            $matches = $this->generateTournamentBrackets($participants,$tournoi); 
            $current_matches = $this->TournoiServices->getMatchesByTournament($tournoi_id);
        } else {
            $current_matches = $existingMatches;
        }


        $matchesByRound = [];
        foreach ($current_matches as $match){
            $round = $match['round'];
            if (!isset($matchesByRound[$round])){
                $matchesByRound[$round] = [];
            }
            $matchesByRound[$round][] = $match;
        }
        
        return $this->renderOrg('bracket',[
            'tournoi' => $tournoi,
            'matchesByRound' => $matchesByRound,
            'participants' => $participants
        ]);
    }

    private function generateTournamentBrackets($participants,$tournoi){
        $matches = [];
        $tournoi_id = $tournoi["id"];
        $participantCount  = count($participants);

        shuffle($participants);

        $roundsNedded = ceil(log($participantCount,2));

        $perfectBracketSize = pow(2,$roundsNedded);

        $byeNeededs = $perfectBracketSize - $participantCount;

        $matchIndex = 1;
        for($i=0 ;$i < $participantCount - $byeNeededs;$i += 2){
            if ($i + 1 < $participantCount){
                $match = [
                    'id' => $matchIndex++,
                    'tournoi_id' => $tournoi_id,
                    'round' => 1,
                    'match_number' => ($i/2) +1,
                    'participant1_id' => $participants[$i]['id'],
                    'participant1_name' => $participants[$i]['name'],
                    'participant2_id' => $participants[$i+1]['id'],
                    'participant2_name' => $participants[$i+1]['name'],
                    'score_participant1' => null,
                    'score_participant2' => null,
                    'winner_id' => null,
                    'scheduled_date' => date('Y-m-d H:i:s', strtotime('+1 day')),
                    'status' => 'scheduled'
                ];

                $matchInsert = $this->TournoiServices->saveMatch($match);
                $matches[] = $match;
            }
        }
        $matchesPerRound = $perfectBracketSize / 2;
        for ($round = 2; $round <= $roundsNedded; $round++){
            $matchesPerRound /= 2;
            for ($m = 1; $m <= $matchesPerRound ; $m++){
                $match = [
                    'id' => $matchIndex++,
                    'tournoi_id' => $tournoi_id,
                    'round' => $round,
                    'match_number' => $m,
                    'participant1_id' => null,
                    'participant1_name' => 'TBD',
                    'participant2_id' => null,
                    'participant2_name' => 'TBD',
                    'score_participant1' => null,
                    'score_participant2' => null,
                    'winner_id' => null,
                    'scheduled_date' => date('Y-m-d H:i:s', strtotime("+$round days")),
                    'status' => 'pending'
                ];

                $this->TournoiServices->saveMatch($match);
                $matches[] = $match;
            }
        }
        return $matches;
    }
}
?>