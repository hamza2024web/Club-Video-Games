<?php
namespace App\Models;
class Genre {
    private $id;
    private $name;
    private $description;
    private$status;

    public function __construct($id,$name,$description,$status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->status = $status;
    }
    public function getId(){
        return $this->id;
    }
    public function getName(){
        return $this->name;
    }
    public function getDescription(){
        return $this->description;
    }
    public function getStatus(){
        return $this->status;
    }
}
?>