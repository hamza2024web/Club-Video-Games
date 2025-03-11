<?php
require_once("../../vendor/autoload.php");

use Google\Client;
use Google\Service\Oauth2; 

class GoogleController {
    private $googleClient;

    public function __construct() {
        $this->googleClient = new Client();
        $this->googleClient->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $this->googleClient->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $this->googleClient->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);
        
        // ✅ Fix: Use Oauth2 instead of Oauth
        $this->googleClient->addScope(Oauth2::USERINFO_EMAIL);
        $this->googleClient->addScope(Oauth2::USERINFO_PROFILE);
    }

    public function redirectToGoogle() {
        $authUrl = $this->googleClient->createAuthUrl();
        header("Location: " . $authUrl);
        exit();
    }

    public function handleGoogleCallback() {
        if (!isset($_GET['code'])) {
            die('No code found.');
        }

        $token = $this->googleClient->fetchAccessTokenWithAuthCode($_GET['code']);
        if (isset($token['error'])) {
            die('Error fetching access token');
        }

        $this->googleClient->setAccessToken($token);
        $oauth2 = new Oauth2($this->googleClient);
        $googleUser = $oauth2->userinfo->get();

        $this->loginOrCreateUser($googleUser);
    }

    private function loginOrCreateUser($googleUser) {
        $userService = new ProfileServices();
        $existingUser = $userService->getUserByEmail($googleUser->email);

        if ($existingUser) {
            $_SESSION["user_id"] = $existingUser["id"];
            header("Location: /profile");
            exit();
        } else {
            $newUser = $userService->createGoogleUser($googleUser);
            $_SESSION["user_id"] = $newUser["id"];
            header("Location: /profile");
            exit();
        }
    }
}
