<?php
namespace App\Services;
use App\Repository\ClubRepository;

class ClubServices {
    protected $clubRepository;

    public function __construct()
    {
        $this->clubRepository = new ClubRepository();
    }
}
?>