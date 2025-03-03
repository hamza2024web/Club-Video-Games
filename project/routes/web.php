<?php
require_once("../vendor/autoload.php");
use Src\Http\Route;

Route::get('','HomeController@index');
Route::get('login','LoginController@index');
Route::post('login','LoginController@login');
Route::get('registre','RegistreController@indexRegistre');
Route::post('registre','RegistreController@registre');
Route::get('dashboard','AdminController@dashboard');
Route::get('clubs','membreController@clubs');
Route::get('club','OrganisateurController@organisateur');
Route::get('users','AdminController@Users');
?>