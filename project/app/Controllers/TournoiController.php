<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\JeuxServices;
use App\Services\ProfileServices;
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
    }

    public function index(){
        $user_id = $_SESSION["user_id"];
        $profile = $this->ProfileServices->getProfileUser($user_id);
        $jeux = $this->JeuxServices->getGame($user_id);
        return $this->renderOrg('tournoi',compact('profile','jeux'));
    }
}
?>