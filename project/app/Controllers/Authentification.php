<?php

namespace App\Controllers;
use App\Models\UserModel;

class AuthController {
    public function login($email , $password){
        $userModel = new UserModel();
        $user = $userModel->loginSession($email , $password);
        
        if ($user['status'] === "Activation"){
            $pathUrl = "/src/Views/";
            if($user['role'] == "administrateur"){
                header("location:" .$pathUrl. "Admin/dashboard.php");
            } 
            else if($user['role'] === "etudiant"){
                header("location:" .$pathUrl. "etudiant/home.php");
            }
            else if($user['role'] === "enseignant"){
                header("location:".$pathUrl. "enseignant/home.php");
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