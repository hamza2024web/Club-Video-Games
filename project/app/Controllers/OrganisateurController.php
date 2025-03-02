<?php
namespace App\Controllers;
use App\Controllers\BaseController;

class OrganisateurController extends BaseController {
    public function __construct()
    {
        parent::__construct();
    }

    public function organisateur(){
        $this->renderOrg('club');
    }
}
?>