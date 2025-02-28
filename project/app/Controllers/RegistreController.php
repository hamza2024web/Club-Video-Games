<?php

namespace App\Controllers;
use App\Services\RegistreServices;

class RegistreController extends BaseController {
    protected $authServices;

    public function __construct()
    {
        $this->authServices = new RegistreServices();     
    }

    public function indexRegistre(){
        return $this->renderAuth('registre');
    }

    public function registre ( $role ,$name , $email , $password , $naissance = null, $club = null){
        $role = $_POST["role"];
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $naissance = $_POST["naissance"];
        $club = $_POST["club"];

        $user = $this->authServices->registresession($role,$name,$email,$password,$naissance,$club);

        if($user == null){
            echo "please fields inputs and create your account ...";
        } else {
            if ($user->getStatus()=="Activation"){
                if($user->getRole()=="administrateur"){
                    header("location:");
                }
                else if($user->getRole()=="membre"){
                    header("location:");
                }
                else if($user->getRole()=="organisateur"){
                    header("location:");
                }
            } elseif ($user->getStatus()=="suspension"){
                header("location:");
                echo "Votre compte a été suspenser";
            } elseif ($user->getStatus()=="Not Active"){
                header("location:");
                echo "Votre compte a été Disactiver";
            }
        }
    }
    }
?>