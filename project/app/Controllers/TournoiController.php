<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\JeuxServices;
use App\Services\ProfileServices;
use App\Services\TournoiServices;

session_start();

class TournoiController extends BaseController{
    protected $TournnoiServices;
    protected $ProfileServices;
    protected $JeuxServices;
    
    public function __construct()
    {
        parent::__construct();
        $this->ProfileServices = new ProfileServices();
        $this->JeuxServices = new JeuxServices();
        $this->TournnoiServices = new TournoiServices();
    }

    public function index(){
        $user_id = $_SESSION["user_id"];
        $profile = $this->ProfileServices->getProfileUser($user_id);
        $jeux = $this->JeuxServices->getGame($user_id);
        $tournois = $this->TournnoiServices->getTournoi($user_id);
        return $this->renderOrg('tournoi',compact('profile','jeux','tournois'));
    }
    public function addTournoi(){
        $user_id = $_SESSION["user_id"];
        $name = $_POST["name"];
        $game = $_POST["game"];
        $status = $_POST["status"];
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

        $saveTournoi = $this->TournnoiServices->setTournoi($user_id,$name,$start_date,$end_date,$max_participants,$status,$rules,$game,$format,$description,$prix_total,$prize_first,$prize_second,$prize_third,$registration_start,$registration_end,$registration_fee,$discord_url,$stream_url,$tournament_photo);

        if ($saveTournoi){
            header("location: /tournoi?Tournoi Created successfully!=1");
        } else {
            header("location: /tournoi?Creation de votre tournoi echouée=1");
        }
    }
}
?>