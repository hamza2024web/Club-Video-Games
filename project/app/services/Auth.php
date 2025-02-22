<?php
require_once ("../../../vendor/autoload.php");
use App\Controllers\AuthController;

if(isset($_POST["submit"])){
    if(empty($_POST["email"]) && empty($_POST[$password])){
        echo "email or password is empty";
    } else {
        $email = $_POST["email"];
        $password = $_POST["password"];

        $authcontroller = new AuthController();
        $authcontroller->login($email , $password);
    }
}

?>