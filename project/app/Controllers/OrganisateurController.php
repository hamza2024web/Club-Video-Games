<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\ProfileServices;

session_start();
class OrganisateurController extends BaseController {
    protected $ProfileServices;
    public function __construct()
    {
        parent::__construct();
        $this->ProfileServices = new ProfileServices();

    }

    public function organisateur(){
        $user_id = $_SESSION["user_id"];
        $profile = $this->ProfileServices->getProfileUser($user_id);
        $this->renderOrg('homePage',compact('profile'));
    }
}
?>