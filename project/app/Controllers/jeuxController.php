<?php
namespace App\Controllers;
use App\Services\JeuxServices;
use App\Controllers\BaseController;
use App\Services\dashboardServices;
use App\Services\ProfileServices;
session_start();

class jeuxController extends BaseController {
    protected $JeuxServices;
    protected $ProfileServices;
    protected $AdminServices;

    public function __construct()
    {
        parent::__construct();
        $this->JeuxServices = new JeuxServices();
        $this->ProfileServices = new ProfileServices();
        $this->AdminServices = new dashboardServices();
    }
    public function index() {
        $user_id = $_SESSION["user_id"];
        $profile = $this->ProfileServices->getProfileUser($user_id);
        $jeuxAchetes = $this->JeuxServices->getGame($user_id);
        $genres = $this->AdminServices->getGenre();
        return $this->renderOrg('jeux', compact('profile', 'jeuxAchetes', 'genres'));
    }
}
?>