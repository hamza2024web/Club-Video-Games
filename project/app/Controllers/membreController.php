<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\MembreServices;
session_start();

class membreController extends BaseController{
    protected $MembreServices;
    public function __construct()
    {
        parent::__construct();
        $this->MembreServices = new MembreServices();
    }

    public function dashboard (){
        $user_id = $_SESSION["user_id"];
        $member = $this->MembreServices->getProfileMembre($user_id);
        $my_solde = $this->solde($user_id);
        $this->renderMem('dashboard',compact('member','my_solde'));
    }

    
}
?>