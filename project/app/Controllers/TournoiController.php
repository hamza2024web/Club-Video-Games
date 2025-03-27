<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\ProfileServices;
session_start();

class TournoiController extends BaseController{
    protected $TournnoiServices;
    protected $ProfileServices;
    
    public function __construct()
    {
        parent::__construct();
        $this->ProfileServices = new ProfileServices();
    }

    public function index(){
        $user_id = $_SESSION["user_id"];
        $profile = $this->ProfileServices->getProfileUser($user_id);
        return $this->renderOrg('tournoi',compact('profile'));
    }
}
?>