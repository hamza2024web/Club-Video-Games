<?php
namespace App\Models;

class Evenement_programme {
    private $timeline_time;
    private $timeline_title;
    private $timeline_desc;

    public function __construct($timeline_time,$timeline_title,$timeline_desc)
    {
        $this->timeline_time = $timeline_time;
        $this->timeline_title = $timeline_title;
        $this->timeline_desc = $timeline_desc;
    }

    public function getTime(){
        return $this->timeline_time;
    }

    public function getTitle(){
        return $this->timeline_title;
    }

    public function getDesc(){
        return $this->timeline_desc;
    }
    
}

?>