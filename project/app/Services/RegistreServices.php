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

    public function registresession($role,$name,$email,$password,$naissance,$club){
        $user = $this->registreRepository->setMembreAndOrganisateur($role,$name,$email,$password,$naissance,$club);
        return $user;
    }
}
?>