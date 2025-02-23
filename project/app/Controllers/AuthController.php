<?php

namespace App\Controllers;
use App\Services\AuthServices;

class AuthController extends BaseController {
    protected $authServices;

    public function __construct()
    {
        $this->authServices = new AuthServices();     
    }

    public function index(){
        return $this->renderAuth('login');
    }
    public function indexRegistre(){
        return $this->renderAuth('registre');
    }

    public function login($email , $password){
        $email = $_POST["email"];
        $password = $_POST["password"];
        
        $user = $this->authServices->loginSession($email , $password);

        if ($user['status'] === "Activation"){
            if($user['role'] == "administrateur"){
                header("location:");
            } 
            else if($user['role'] === "membre"){
                header("location:");
            }
            else if($user['role'] === "organisateur"){
                header("location:");
            }
        } else {
            if ($user['status'] === "suspension"){
                echo "Votre compte a été suspenser";
            }
            else if($user['status'] === "Not Active"){
                echo "Votre compte a été encore désactivé";
            }
        }
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