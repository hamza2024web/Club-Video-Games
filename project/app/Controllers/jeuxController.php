<?php
namespace App\Controllers;
use App\Services\JeuxServices;
use App\Controllers\BaseController;

class jeuxController extends BaseController {
    protected $JeuxServices;

    public function __construct()
    {
        parent::__construct();
        $this->JeuxServices = new JeuxServices();
    }
    public function index(){
        $this->renderOrg('jeux');
    }
}
?>