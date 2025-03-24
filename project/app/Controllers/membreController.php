<?php
namespace App\Controllers;
use App\Controllers\BaseController;


class membreController extends BaseController{
    public function __construct()
    {
        parent::__construct();
    }

    public function dashboard (){
        $this->renderMem('dashboard');
    }

    
}
?>