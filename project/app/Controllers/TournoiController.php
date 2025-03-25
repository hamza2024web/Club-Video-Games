<?php
namespace App\Controllers;
use App\Controllers\BaseController;

class TournoiController extends BaseController{
    protected $TournnoiServices;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        return $this->renderOrg('tournoi');
    }
}
?>