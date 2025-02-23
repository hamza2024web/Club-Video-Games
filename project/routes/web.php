<?php
require_once("../vendor/autoload.php");
use Src\Http\Route;

Route::get('','HomeController@index');
Route::get('login','AuthController@index');
Route::post('login','AuthController@login');
Route::get('registre','AuthController@indexRegistre')
Route::post('registre','AuthController@registre')
?>