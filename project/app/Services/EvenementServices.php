<?php
namespace App\Services;
use App\Repository\EvenementRepository;

class EvenementServices {
    protected $EventRepository;
    public function __construct()
    {
        $this->EventRepository = new EvenementRepository();
    }

    public function setEvent(){
        
    }
}
?>