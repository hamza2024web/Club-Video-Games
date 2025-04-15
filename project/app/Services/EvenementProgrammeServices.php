<?php
namespace App\Services;
use App\Repository\EvenementProgrammeRepository;

class EvenementProgrammeServices {
    protected $EvenementProgrammeRepository;

    public function __construct()
    {
        $this->EvenementProgrammeRepository = new EvenementProgrammeRepository();
    }

    public function getByEvenementId($event_id){
        $event = $this->EvenementProgrammeRepository->getEventById($event_id);
        return $event;
    }
}
?>