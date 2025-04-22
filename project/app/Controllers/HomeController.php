<?php
namespace App\Controllers;

use App\Services\dashboardServices;
use App\Services\EventsMemberServices;

class HomeController extends BaseController{
    protected $dashboardServices;
    protected $EvenementServices;

    public function __construct()
    {
        parent::__construct();
        $this->dashboardServices = new dashboardServices();
        $this->EvenementServices = new EventsMemberServices();
    }

    public function index(){
        $events = $this->EvenementServices->getAllEvents();
        $games = $this->dashboardServices->getGame();
        $home = $this->render('home',compact('games','events'));
        return $home;
    }
}

?>