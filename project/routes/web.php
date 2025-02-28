<?php
require_once("../vendor/autoload.php");
use Src\Http\Route;

Route::get('','HomeController@index');
Route::get('login','LoginController@index');
Route::post('login','LoginController@login');
Route::get('registre','RegistreController@indexRegistre');
Route::post('registre','RegistreController@registre');
?>