<?php
namespace App\Controllers;
use App\Controllers\BaseController;

class AdminController extends BaseController{
    public function __construct()
    {
        parent::__construct();
    }

    public function dashboard (){
        $this->renderAdmin('dashboard');
    }
}

?>