<?php
namespace App\Controllers;

use App\Services\dashboardServices;

class HomeController extends BaseController{
    protected $dashboardServices;

    public function __construct()
    {
        parent::__construct();
        $this->dashboardServices = new dashboardServices();
    }
    public function index(){
        $games = $this->dashboardServices->getGame();
        $home = $this->render('home',compact('games'));
        return $home;
    }
}

?>