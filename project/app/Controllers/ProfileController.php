<?php
namespace App\Controllers;
use App\Controllers\BaseController;


class ProfileController extends BaseController{

    public function __construct()
    {
        parent::__construct();
    }
    public function profile (){
        return $this->renderOrg('profile');
    }
    public function updateProfile(){
        
    }
}

?>