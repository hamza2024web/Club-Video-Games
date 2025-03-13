<?php
namespace App\Services;
use App\Repository\LoginRepository;
use App\Repository\MembreAndOrgan;
use Google_Client; 

class RegistreServices {
    protected $userRepository;
    protected $registreRepository;

    public function __construct()
    {
        $this->userRepository = new LoginRepository();
        $this->registreRepository = new MembreAndOrgan();
    }

    public function registresession($role,$name,$email,$password,$naissance,$club){
        $user = $this->registreRepository->setMembreAndOrganisateur($role,$name,$email,$password,$naissance,$club);
        return $user;
    }

}
?>