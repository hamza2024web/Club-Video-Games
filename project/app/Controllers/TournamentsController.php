<?php
namespace App\Controllers;

use App\Services\MembreServices;
use App\Services\TournoiServices;
session_start();

class TournamentsController extends BaseController {
    protected $TournoiServices;
    protected $MembreServices;
    public function __construct()
    {
        parent::__construct();
        $this->TournoiServices = new TournoiServices();
        $this->MembreServices = new MembreServices();
    }

    public function index(){
        $user_id = $_SESSION["user_id"];
        $member = $this->MembreServices->getProfileMembre($user_id);
        return $this->renderMem('tournaments',compact('member'));
    }
}
?>