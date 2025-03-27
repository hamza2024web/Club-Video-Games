<?php
namespace App\Controllers;
use App\Controllers\BaseController;


class JeuxController extends BaseController{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        return $this->renderOrg('jeux');
    }
}
?>