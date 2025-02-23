<?php
namespace App\Services;
use App\Repository\UserRepository;
use App\Repository\MembreAndOrgan;

class AuthServices {
    protected $userRepository;
    protected $registreRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->registreRepository = new MembreAndOrgan();
    }

    public function loginSession($email , $password){
        $user = $this->userRepository->findUserByEmail($email);
        
        if (!$user){
            return false;
        }
        if ($user['role'] === 'administrateur'){
            return $user;
        }
        
        if(password_verify($password , $user['password'])){
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
    public function registresession($role,$name,$email,$password,$naissance,$club){
        $user = $this->registreRepository->setMembreAndOrganisateur($role,$name,$email,$password,$naissance,$club);
        return $user;
    }
}
?>