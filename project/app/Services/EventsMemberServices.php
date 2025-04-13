<?php
namespace App\Services;
use App\Repository\EventsMemberRepository;

class EventsMemberServices {
    protected $EventsMemberRepository;
    public function __construct()
    {
        $this->EventsMemberRepository = new EventsMemberRepository();
    }

    public function getAllEvents(){
        $events = $this->EventsMemberRepository->getEvents();
        return $events;
    }
}
?>