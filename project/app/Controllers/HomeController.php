<?php
namespace App\Controllers;


class HomeController extends BaseController{
    public function index(){
        $home = $this->render('home');
        return $home;
    }
}

?>