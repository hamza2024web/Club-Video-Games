<?php

namespace App\Services;

use App\Repository\LoginRepository;
use App\Repository\MembreAndOrgan;
use Google_Client;

class LoginServices
{
    protected $userRepository;
    protected $registreRepository;

    public function __construct()
    {
        $this->userRepository = new LoginRepository();
        $this->registreRepository = new MembreAndOrgan();
    }
    public function getUserByEmail($googleUser){
        $email = $this->userRepository->findUserByEmail($googleUser->email);
        return $email;
    }

    public function loginSession($email, $password){
        $user = $this->userRepository->findUserByEmail($email);

        if (!$user) {
            return false;
        }
        if ($user['role'] === 'administrateur') {
            return $user;
        }

        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['status'] = $user['status'];

            error_log("Login successfly for email: $email");
            return $user;
        } else {
            return false;
        }
    }

    public function loginWithGoogle($credential){ 
        $clientId = "16802501273-egl3p0sb8f1a4hrjp3unu1pa80ckn0o5.apps.googleusercontent.com";
        $client = new Google_Client(['client_id' => $clientId]);
        $id_token = $credential;
        $user = $client->verifyIdToken($id_token);
        if($user){
            $email = $user['email'];
            $result = $this->userRepository->findUserByEmail($email);
        }
        if ($result){
            $user = $result['user']; 
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['status'] = $user['status'];
    
            error_log("Login successfly for email: $email");
            return $user;
        } else {
            $data = [
                'name' => $user['name'],
                'email' => $user['email']
            ];
            return $data;
        }

    }   

}
