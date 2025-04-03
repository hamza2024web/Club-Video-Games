<?php
namespace App\Services;
use App\Repository\JeuxRepository;


class JeuxServices {
    protected $JeuxRepository;

    public function __construct()
    {
        $this->JeuxRepository = new JeuxRepository();
    }
}
?>