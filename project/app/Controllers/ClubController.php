<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\ClubServices;

class ClubController extends BaseController {
    protected $clubServices;
    public function __construct()
    {
        parent::__construct();
        $this->clubServices = new ClubServices();
    }
    public function index (){
        return $this->renderOrg('ClubManagement');
    }
}
?>