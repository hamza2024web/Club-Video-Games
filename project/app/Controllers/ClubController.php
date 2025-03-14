<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\ClubServices;
use App\Services\ProfileServices;
session_start();

class ClubController extends BaseController {
    protected $clubServices;
    protected $profileServices;
    public function __construct()
    {
        parent::__construct();
        $this->clubServices = new ClubServices();
        $this->profileServices = new ProfileServices();
    }
    public function index (){
        $user_id = $_SESSION["user_id"];
        $profile = $this->profileServices->getProfileUSer($user_id);
        return $this->renderOrg('ClubManagement',compact('profile'));
    }
}
?>