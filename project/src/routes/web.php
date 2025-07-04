<?php
require_once("../vendor/autoload.php");
use Src\Http\Route;

Route::get('','HomeController@index');
Route::get('/about','HomeController@about');

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
Route::get('/member/tournaments','TournamentsController@index');
Route::post('/member/inscription','TournamentsController@inscriptionTournoi');
Route::get('/member/boutique','BoutiqueMember@index');
Route::get('/member/games','JeuxMember@index');
Route::get('/member/events','EvenetsMemberController@index');
Route::post('/member/eventInscription','EvenetsMemberController@inscription');
Route::post('/member/notificationRead','EvenetsMemberController@readNotification');

// organisateur
Route::get('profile','ProfileController@profile');
Route::post('updateProfile','ProfileController@updateProfile');
Route::post('updatePassword','ProfileController@UpdatePassword');
Route::get('ClubManagement','ClubController@index');
Route::post('clubForm','ClubController@updateClub');
Route::get('tournoi','TournoiController@index');
Route::get('boutique','BoutiqueController@index');
Route::get('jeux','jeuxController@index');
Route::post('organisateur/addTournoi','TournoiController@addTournoi');
Route::get('organisateur/evenement','EvenementController@index');
Route::post('organisateur/addEvenement','EvenementController@addEvenement');
Route::post('organisateur/cancelEvent','EvenementController@cancelEvent');
Route::post('/organisateur/notificationRead','EvenementController@readNotification');
Route::post('/tournoi/bracket','TournoiController@showTournamentBracket');
Route::post('/tournoi/updateMatchResult','TournoiController@updateMatchResult');

// payment
Route::post('payer','PaymentController@payer');

// compte
Route::post('rechargeCompte','CompteController@rechargeCompte');
?>