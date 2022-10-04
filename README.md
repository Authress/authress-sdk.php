# AuthressSDK


## Installation & Usage
Install authress-sdk for usage of the API:

`composer require authress/authress-sdk.php`

## SDK usage

There's a full working example in this repository for how to flow can look like: [working example](./integrationTest/mock/index.php);

The full flow is usually:
* Check if user is logged in using `userSessionExists`
* If user is not logged in send them to your login page
  * On your login page ask the user which connection provider they want to use
  * Call `authenticate` with that `connectionId`
  * User will be redirected to the provider
* User will be returned to your redirectUrl
  * The redirectUrl page should include a code snippet from the [working example](./integrationTest/mock/index.php) and in the URL of your page will contain the `iss`, `code`, and `nonce` parameters (for validation, you don't need to worry how to handle them, but they need to be there this signals that the login worked correctly.)
* Call the `userSessionExists` method
  * This should work and return `true` at this moment

  You can also validate this worked by looking at the cookies for the app, which should contain `authorization` and `user` cookies. Please don't use these cookies directly as their format might change.

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
    'connectionId' => "CONNECTION_ID"
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

Verify an incoming token into your service from a client:
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
$accessTokenClaims = $authressClient->login->verifyToken($token);
echo json_encode($accessTokenClaims);

// Or set it as the `Authorization Header` to call another service:

$client->request('POST', 'https://api.application.com', [
    headers => [
        'Authorization' => 'Bearer ' . $token
    ]
]);

?>
```