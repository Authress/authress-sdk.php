# AuthressSDK


## Installation & Usage
Install authress-sdk for usage of the API:

`composer require authress/authress-sdk.php`

## SDK usage

```php
<?php
use AuthressSdk\AuthressClient;
use AuthressSdk\ApiException;

// create an instance of the API class during service initialization
// Replace DOMAIN with the Authress domain for your account
$authressClient = new AuthressClient('https://DOMAIN.api-REGION.authress.io');
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