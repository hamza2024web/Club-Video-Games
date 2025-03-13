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

    private function verifyToken($postData){
        $csrf_token_cookie = $_COOKIE['g_csrf_token'] ?? null;
        $csrf_token_body = $postData['g_csrf_token'] ?? null;

        if (!$csrf_token_cookie) {
            http_response_code(400);
            die('No CSRF token in Cookie.');
        }

        if (!$csrf_token_body) {
            http_response_code(400);
            die('No CSRF token in post body.');
        }

        if (hash_equals($csrf_token_cookie, $csrf_token_body) === false) {
            http_response_code(400);
            die('Failed to verify double submit cookie.');
        }
    }
    
    public function loginGoogle(){
        $postData = $_POST;
        $credential = $_POST['credential'] ?? null;
        $this->verifyToken($postData);
        $userServices = $this->authServices->loginWithGoogle($credential);
        if(is_array($userServices)){
            $data = $userServices;
            $this->renderAuth('formGoogle',compact('data'));
        } else {
            if ($userServices['status'] === "Activation"){
                if($userServices['role'] == "administrateur"){
                    header("location:/dashboard");
                } 
                else if($userServices['role'] === "membre"){
                    header("location: /clubs");
                }
                else if($userServices['role'] === "organisateur"){
                    header("location: /homePage");
                }
            } else {
                if ($userServices['status'] === "suspension"){
                    echo "Votre compte a été suspendu";
                }
                else if($userServices['status'] === "Not Active"){
                    echo "Votre compte est encore désactivé";
                }
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