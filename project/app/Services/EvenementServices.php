<?php
namespace App\Services;
use App\Repository\EvenementRepository;

class EvenementServices {
    protected $EventRepository;
    public function __construct()
    {
        $this->EventRepository = new EvenementRepository();
    }

    public function getMyEvents($user_id){
        $events = $this->EventRepository->fetchAllEvents($user_id);
        return $events;
    }

    public function setEvent($user_id,$name_event,$registration_start,$registration_end,$type_event,$status,$location,$event_date,$event_time,$event_photo,$max_participants,$entry_fee,$description,$requirements,$timeline_time,$timeline_title,$timeline_desc,$discord_url,$twitch_url){
        $is_inserted = $this->EventRepository->addEvent($user_id,$name_event,$registration_start,$registration_end,$type_event,$status,$location,$event_date,$event_time,$event_photo,$max_participants,$entry_fee,$description,$requirements,$timeline_time,$timeline_title,$timeline_desc,$discord_url,$twitch_url);
        return $is_inserted;
    }
}
?>