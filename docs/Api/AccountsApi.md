# AuthressClient\AccountsApi

All URIs are relative to */*

Method | HTTP request | Description
------------- | ------------- | -------------
[**getAccount**](AccountsApi.md#getaccount) | **GET** /v1/accounts/{accountId} | Get account information.
[**getAccountIdentities**](AccountsApi.md#getaccountidentities) | **GET** /v1/identities | Get all linked identities for this account.
[**getAccounts**](AccountsApi.md#getaccounts) | **GET** /v1/accounts | Get all accounts user has access to
[**linkIdentity**](AccountsApi.md#linkidentity) | **POST** /v1/identities | Link a new account identity.

# **getAccount**
> \AuthressClient\Model\InlineResponse2007 getAccount($account_id)

Get account information.

Includes the original configuration information.         <br><span class=\"badge badge-outline-secondary\">READ: Authress:Configuration</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new AuthressClient\Api\AccountsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$account_id = "account_id_example"; // string | The unique identifier for the account

try {
    $result = $apiInstance->getAccount($account_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountsApi->getAccount: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **account_id** | **string**| The unique identifier for the account |

### Return type

[**\AuthressClient\Model\InlineResponse2007**](../Model/InlineResponse2007.md)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/links+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getAccountIdentities**
> \AuthressClient\Model\InlineResponse20010 getAccountIdentities()

Get all linked identities for this account.

Returns a list of identities linked for this account.         <br><span class=\"badge badge-outline-secondary\">READ: Authress:Configuration</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new AuthressClient\Api\AccountsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $result = $apiInstance->getAccountIdentities();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountsApi->getAccountIdentities: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters
This endpoint does not need any parameter.

### Return type

[**\AuthressClient\Model\InlineResponse20010**](../Model/InlineResponse20010.md)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/links+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getAccounts**
> \AuthressClient\Model\InlineResponse2009 getAccounts()

Get all accounts user has access to

Returns a list of accounts that the user has access to.         <br><span class=\"badge badge-outline-secondary\">READ: Authress:Configuration</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new AuthressClient\Api\AccountsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $result = $apiInstance->getAccounts();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountsApi->getAccounts: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters
This endpoint does not need any parameter.

### Return type

[**\AuthressClient\Model\InlineResponse2009**](../Model/InlineResponse2009.md)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/links+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **linkIdentity**
> linkIdentity($body)

Link a new account identity.

An identity is a JWT subscriber *sub* and issuer *iss*. Only one account my be linked to a particular JWT combination. Allows calling the API with a federated token directly instead of using a client access key.         <br><span class=\"badge badge-outline-secondary\">UPDATE: Authress:Configuration</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new AuthressClient\Api\AccountsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \AuthressClient\Model\Body7(); // \AuthressClient\Model\Body7 | 

try {
    $apiInstance->linkIdentity($body);
} catch (Exception $e) {
    echo 'Exception when calling AccountsApi->linkIdentity: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\AuthressClient\Model\Body7**](../Model/Body7.md)|  |

### Return type

void (empty response body)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

