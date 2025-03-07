<?php
require_once("../vendor/autoload.php");
use Src\Http\Route;

Route::get('','HomeController@index');
Route::get('login','LoginController@index');
Route::post('login','LoginController@login');
Route::get('registre','RegistreController@indexRegistre');
Route::post('registre','RegistreController@registre');
Route::get('logout','LoginController@logout');
Route::get('dashboard','AdminController@dashboard');
Route::get('clubs','membreController@clubs');
Route::get('club','OrganisateurController@organisateur');
Route::get('users','AdminController@Users');
Route::post('updateStatus','AdminController@updateStatus');
Route::get('genre','AdminController@genre');
Route::post('addGenre','AdminController@addGenre');
?>