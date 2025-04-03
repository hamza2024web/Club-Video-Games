<?php
require_once("../vendor/autoload.php");
use Src\Http\Route;

Route::get('','HomeController@index');

// Authentification
Route::get('login','LoginController@index');
Route::post('login','LoginController@login');
Route::post('loginGoogle','LoginController@loginGoogle');
Route::post('googleForm','RegistreController@registre');
Route::get('registre','RegistreController@indexRegistre');
Route::post('registre','RegistreController@registre');
Route::get('logout','LoginController@logout');

// Administrateur
Route::get('dashboard','AdminController@dashboard');
Route::get('users','AdminController@Users');
Route::post('updateStatus','AdminController@updateStatus');
Route::get('genre','AdminController@genre');
Route::post('addGenre','AdminController@addGenre');
Route::post('deleteGenre','AdminController@deleteGenre');
Route::post('editGenre','AdminController@editGenre');
Route::get('Game','AdminController@game');
Route::post('addGame','AdminController@addGame');
Route::post('updateGame','AdminController@EditGame');
Route::post('deleteGame','AdminController@deleteGame');

// Member
Route::get('member/dashboard','membreController@dashboard');
Route::get('member/profile','ProfileMembre@profile');
Route::post('member/profile/edit','ProfileMembre@updateProfile');
Route::post('member/password/change','ProfileMembre@UpdatePassword');

// organisateur
Route::get('homePage','OrganisateurController@organisateur');
Route::get('profile','ProfileController@profile');
Route::post('updateProfile','ProfileController@updateProfile');
Route::post('updatePassword','ProfileController@UpdatePassword');
Route::get('ClubManagement','ClubController@index');
Route::post('clubForm','ClubController@updateClub');
Route::get('tournoi','TournoiController@index');
Route::get('boutique','BoutiqueController@index');
Route::get('jeux','jeuxController@index');
// payment 
Route::post('payer','PaymentController@payer');
?>