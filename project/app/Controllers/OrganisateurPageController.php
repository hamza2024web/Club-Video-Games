<?php
namespace App\Controllers;
use App\Controllers\BaseController;

class OrganisateurPageController extends BaseController{
    public function __construct()
    {
        parent::__construct();
    }
    public function homePage (){
        return $this->renderOrg('homePage');
    }
}
?>