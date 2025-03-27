<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\dashboardServices;
use App\Services\ProfileServices;
session_start();    

class JeuxController extends BaseController{
    protected $AdminServices ;
    protected $ProfileServices;
    public function __construct()
    {
        parent::__construct();
        $this->AdminServices = new dashboardServices();
        $this->ProfileServices = new ProfileServices();
    }

    public function index(){
        $user_id = $_SESSION["user_id"];
        $profile = $this->ProfileServices->getProfileUser($user_id);
        $jeux = $this->AdminServices->getGame();
        return $this->renderOrg('jeux',compact('jeux','profile'));
    }
}
?>