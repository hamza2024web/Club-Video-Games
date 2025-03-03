<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Services\LoginServices;
class LoginController extends BaseController {
    protected $authServices;

    public function __construct()
    {
        parent::__construct();
        $this->authServices = new LoginServices();     
    }

    public function index(){
        return $this->renderAuth('login');
    }

    public function login(){
        $email = $_POST["email"];
        $password = $_POST["password"];
        
        $user = $this->authServices->loginSession($email , $password);
        if ($user['status'] === "Activation"){
            if($user['role'] == "administrateur"){
                header("location:/dashboard");
            } 
            else if($user['role'] === "membre"){
                header("location:/clubs");
            }
            else if($user['role'] === "organisateur"){
                header("location:/club");
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
}
?>