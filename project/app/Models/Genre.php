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
}
?>