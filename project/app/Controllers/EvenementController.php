<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\JeuxServices;
use App\Services\ProfileServices;

session_start();

class EvenementController extends BaseController{
    protected $EvenementServices;
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
        $my_solde = $this->solde($user_id);
        $profile = $this->ProfileServices->getProfileUser($user_id);
        $jeux = $this->JeuxServices->getGame($user_id);
        return $this->renderOrg('evenement',compact('profile','jeux','my_solde'));
    }
}
?>