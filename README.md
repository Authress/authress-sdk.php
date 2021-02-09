# AuthressSDK


## Installation & Usage
Install authress-sdk for usage of the API:

`composer require authress/authress-sdk.php`

## SDK usage

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


$apiInstance = new AuthressClient\Api\AccessRecordsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \AuthressClient\Model\Body6(); // \AuthressClient\Model\Body6 | 

try {
    $result = $apiInstance->createClaim($body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccessRecordsApi->createClaim: ', $e->getMessage(), PHP_EOL;
}


$apiInstance = new AuthressClient\Api\AccessRecordsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \AuthressClient\Model\Body3(); // \AuthressClient\Model\Body3 | 

try {
    $apiInstance->createInvite($body);
} catch (Exception $e) {
    echo 'Exception when calling AccessRecordsApi->createInvite: ', $e->getMessage(), PHP_EOL;
}


$apiInstance = new AuthressClient\Api\AccessRecordsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \AuthressClient\Model\Body1(); // \AuthressClient\Model\Body1 | 

try {
    $result = $apiInstance->createRecord($body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccessRecordsApi->createRecord: ', $e->getMessage(), PHP_EOL;
}


$apiInstance = new AuthressClient\Api\AccessRecordsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$invite_id = "invite_id_example"; // string | The identifier of the invite.

try {
    $apiInstance->deleteInvite($invite_id);
} catch (Exception $e) {
    echo 'Exception when calling AccessRecordsApi->deleteInvite: ', $e->getMessage(), PHP_EOL;
}


$apiInstance = new AuthressClient\Api\AccessRecordsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$record_id = "record_id_example"; // string | The identifier of the access record.

try {
    $apiInstance->deleteRecord($record_id);
} catch (Exception $e) {
    echo 'Exception when calling AccessRecordsApi->deleteRecord: ', $e->getMessage(), PHP_EOL;
}


$apiInstance = new AuthressClient\Api\AccessRecordsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$record_id = "record_id_example"; // string | The identifier of the access record.

try {
    $result = $apiInstance->getRecord($record_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccessRecordsApi->getRecord: ', $e->getMessage(), PHP_EOL;
}


$apiInstance = new AuthressClient\Api\AccessRecordsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$limit = 20; // int | Max number of results to return
$cursor = "cursor_example"; // string | Continuation cursor for paging (will automatically be set)
$filter = "filter_example"; // string | Filter to search records by. This is a case insensitive search through every text field.
$status = "status_example"; // string | Filter records by their current status.

try {
    $result = $apiInstance->getRecords($limit, $cursor, $filter, $status);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccessRecordsApi->getRecords: ', $e->getMessage(), PHP_EOL;
}


$apiInstance = new AuthressClient\Api\AccessRecordsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$invite_id = "invite_id_example"; // string | The identifier of the invite.

try {
    $result = $apiInstance->respondToInvite($invite_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccessRecordsApi->respondToInvite: ', $e->getMessage(), PHP_EOL;
}


$apiInstance = new AuthressClient\Api\AccessRecordsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \AuthressClient\Model\Body2(); // \AuthressClient\Model\Body2 | 
$record_id = "record_id_example"; // string | The identifier of the access record.

try {
    $result = $apiInstance->updateRecord($body, $record_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccessRecordsApi->updateRecord: ', $e->getMessage(), PHP_EOL;
}
?>
```