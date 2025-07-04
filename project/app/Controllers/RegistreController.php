<?php

namespace App\Controllers;
use App\Services\RegistreServices;
use App\Controllers\BaseController;

class RegistreController extends BaseController {
    protected $authServices;

    public function __construct()
    {
        parent::__construct();
        $this->authServices = new RegistreServices();     
    }

    public function indexRegistre(){
        return $this->renderAuth('registre');
    }

    public function registre (){
        $role = $_POST["role"];
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $naissance = $_POST["naissance"];
        $club = $_POST["nam_club"];

        $user = $this->authServices->registresession($role,$name,$email,$password,$naissance,$club);

        if($user == null){
            echo "please fields inputs and create your account ...";
        } else {
            if ($user->getStatus()=="Activation"){
                if($user->getRole()=="administrateur"){
                    header("location:/login?registre_successfully=1");
                }
                else if($user->getRole()=="membre"){
                    header("location:/login?registre_successfully=1");
                }
                else if($user->getRole()=="organisateur"){
                    header("location:/login?registre_successfully=1");
                }
            } elseif ($user->getStatus()=="suspension"){
                header("location:/login?compte_Susspendu=1");
            } elseif ($user->getStatus()=="Not Active"){
                header("location:/login?compte_desactiver=1");
            }
        }
    }

    }
?>