<?php
namespace App\Services;

class EvenementProgrammeService {
    protected $EvenementProgrammeRepository;

    public function __construct()
    {
        $this->EvenementProgrammeRepository = new EvenementProgrammeRepository();
    }
}
?>