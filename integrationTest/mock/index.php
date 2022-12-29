<?php
/**
 * UI Test

 * @category Class
 * @package  AuthressSdk
 * @author   Authress Developers
 * @link     https://authress.io/app/#/api
 */

require 'vendor/autoload.php';

use AuthressSdk\AuthressClient;
use AuthressSdk\Login\AuthenticationParameters;

// create an instance of the API class during service initialization: https://authress.io/app/#/setup?focus=domain
$authressCustomDomain = "https://login.application.com";
// The application that the user is logging in with https://authress.io/app/#/setup?focus=applications
$applicationId = "app_APPLICATION_ID";
$authressClient = new AuthressClient($authressCustomDomain, $applicationId);

$options = new AuthenticationParameters(
    [
    // When user clicks "Log in with Google (or Github)" pass the relevant connectionId here:  https://authress.io/app/#/setup?focus=connections
    'connectionId' => "google"
    ]
);

// Returns true if the user is successfully logged in, and otherwise redirects the user to appropriate login page
session_start();

$sessionExists = $authressClient->login->userSessionExists($options);
echo "Session Exists: " . $sessionExists . "<br>";

if (!$sessionExists) {
    $result = $authressClient->login->authenticate($options);
    echo "Login Result: " . $result . "<br>";
    echo "Issuer: " . $authressClient->login->getUserIdentity()->iss . "<br>";
    echo "UserId: " . $authressClient->login->getUserIdentity()->sub . "<br>";
}

$verifiedUserObject = $authressClient->login->verifyToken();
echo "UserObject: " . json_encode($verifiedUserObject) . "<br>";
echo "Access Token: " . $authressClient->login->getToken() . "<br>";

?>
