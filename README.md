# AuthressSDK


## Installation & Usage
Install authress-sdk for usage of the API:

`composer require authress/authress-sdk.php`

## SDK usage


### Authorization

```php
<?php
require('vendor/autoload.php');
use AuthressSdk\AuthressClient;
use AuthressSdk\ApiException;

// create an instance of the API class during service initialization
$authressCustomDomain = "https://login.application.com";
$authressClient = new AuthressClient($authressCustomDomain);
$authressClient->setApiKey('eyJ...');

// OR: Set the user's access token per request
$authressClient->setAccessToken("user-JWT");

$apiInstance = new \AuthressSdk\Api\UserPermissionsApi($authressClient);

try {
    $userId = "test-userId";
    $resourceUri = "test-resource";
    $permission = "test-permission";
    $result = $apiInstance->authorizeUser($userId, $resourceUri, $permission);
} catch (ApiException $e) {
    if ($e->getStatusCode() === 404 || $e->getStatusCode() === 403) {
        return false;
    }
    throw $e;
}
?>
```

### Authentication

Log the user in with the selected identity provider connection. Use when the user isn't logged in yet.

```php
<?php
require('vendor/autoload.php');
use AuthressSdk\AuthressClient;
use AuthressSdk\Login\AuthenticationParameters;

// create an instance of the API class during service initialization: https://authress.io/app/#/setup?focus=domain
$authressCustomDomain = "https://login.application.com";
// The application that the user is logging in with https://authress.io/app/#/setup?focus=applications
$applicationId = 'app_APPLICATION_ID';
$authressClient = new AuthressClient($authressCustomDomain, $applicationId);

$options = new AuthenticationParameters([
    // When user clicks "Log in with Google (or Github)" pass the relevant connectionId here:  https://authress.io/app/#/setup?focus=connections
    'connectionId' => "CONNECTION_ID",
	'redirectUrl' => "URL_AFTER_SUCCESS_LOGIN (Optional)"
]);
]);

// Returns true if the user is successfully logged in, and otherwise redirects the user to appropriate login page
session_start();
$result = $authressClient->login->authenticate($options);
?>
```

Check if the user is currently logged in. If the user isn't logged in yet, call `$authressClient->login->authenticate()` above.

```php
<?php
require('vendor/autoload.php');
use AuthressSdk\AuthressClient;

// create an instance of the API class during service initialization: https://authress.io/app/#/setup?focus=domain
$authressCustomDomain = "https://login.application.com";
// The application that the user is logging in with https://authress.io/app/#/setup?focus=applications
$applicationId = "app_APPLICATION_ID";
$authressClient = new AuthressClient($authressCustomDomain, $applicationId);

// Returns true if the user is successfully logged in, and otherwise redirects the user to appropriate login page.
session_start();
$isUserLoggedIn = $authressClient->login->userSessionExists();
if (!$isUserLoggedIn) {
    // When the user isn't logged in, send them to the login page
    header("Location: ./login.php");
    exit();
}

// Optionally get access to the user's authorization access token, this token can be explicitly used to call other APIs including Authress authorization as the user.
$userToken = $authressClient->login->getToken();

?>
```
