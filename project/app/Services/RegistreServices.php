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

    public function RegistreWithGoogle($credential){
        $clientId = "16802501273-egl3p0sb8f1a4hrjp3unu1pa80ckn0o5.apps.googleusercontent.com";
        $client = new Google_Client(['client_id' => $clientId]);
        $id_token = $credential;
        $user = $client->verifyIdToken($id_token);
        $user = $this->registreRepository->setMembreAndOrganisateur();

    }

}
?>