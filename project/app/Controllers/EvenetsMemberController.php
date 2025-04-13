<?php
namespace App\Controllers;

use App\Services\MembreServices;

session_start();

class EvenetsMemberController extends BaseController {
    protected $EvenetsMemberServices;
    protected $MembreServices;

    public function __construct()
    {
        parent::__construct();
        // $this->EvenetsMemberServices = new 
        $this->MembreServices = new MembreServices();

    }

    public function index (){
        $user_id = $_SESSION["user_id"];
        $member = $this->MembreServices->getProfileMembre($user_id);
        $my_solde = $this->solde($user_id);
        return $this->renderMem('evenements',compact('member','my_solde'));
    }
}
?>