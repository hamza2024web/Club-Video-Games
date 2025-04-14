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

    public function cancelEventWithRemborse($user_id,$event_id){
        $is_success = $this->reimburseMembers($event_id);
        if ($is_success){
            $is_canceled = $this->CancelAnEvent($user_id,$event_id);
            return $is_canceled;
        } else {
            return false;
        }
    }

    private function reimburseMembers($event_id){
        $isreimburse = $this->EventRepository->reimburseMembersForEvent($event_id);
        return $isreimburse;
    }

    private function CancelAnEvent($user_id,$event_id){
        $is_canceled = $this->EventRepository->cancelEvenement($user_id,$event_id);
        return $is_canceled;
    }
}
?>