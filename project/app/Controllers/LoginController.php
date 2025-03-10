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

        if (!$user || !is_array($user)) {
            echo "Email ou mot de passe incorrect.";
            return;
        }
    
        if ($user['status'] === "Activation"){
            if($user['role'] == "administrateur"){
                header("location:/dashboard");
            } 
            else if($user['role'] === "membre"){
                header("location: /clubs");
            }
            else if($user['role'] === "organisateur"){
                header("location: /homePage");
            }
        } else {
            if ($user['status'] === "suspension"){
                echo "Votre compte a été suspendu";
            }
            else if($user['status'] === "Not Active"){
                echo "Votre compte est encore désactivé";
            }
        }
    }

    public function logout(){
        session_start();
        session_destroy();
        header("location: /login");
    }
}
?>