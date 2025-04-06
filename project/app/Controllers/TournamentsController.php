<?php
namespace App\Controllers;

use App\Services\TournoiServices;

class TournamentsController extends BaseController {
    protected $TournoiServices;
    public function __construct()
    {
        parent::__construct();
        $this->TournoiServices = new TournoiServices();
    }

    public function index(){
        return $this->renderMem('tournaments');
    }
}
?>