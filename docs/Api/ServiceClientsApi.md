# AuthressClient\ServiceClientsApi

All URIs are relative to */*

Method | HTTP request | Description
------------- | ------------- | -------------
[**createClient**](ServiceClientsApi.md#createclient) | **POST** /v1/clients | Create a new client.
[**deleteAccessKey**](ServiceClientsApi.md#deleteaccesskey) | **DELETE** /v1/clients/{clientId}/access-keys/{keyId} | Remove an access key for a client.
[**deleteClient**](ServiceClientsApi.md#deleteclient) | **DELETE** /v1/clients/{clientId} | Delete a client.
[**getClient**](ServiceClientsApi.md#getclient) | **GET** /v1/clients/{clientId} | Get a client.
[**getClients**](ServiceClientsApi.md#getclients) | **GET** /v1/clients | Get clients collection.
[**requestAccessKey**](ServiceClientsApi.md#requestaccesskey) | **POST** /v1/clients/{clientId}/access-keys | Request a new access key.
[**updateClient**](ServiceClientsApi.md#updateclient) | **PUT** /v1/clients/{clientId} | Update a client.

# **createClient**
> \AuthressClient\Model\InlineResponse20012 createClient($body)

Create a new client.

Creates a service client to interact with Authress or any other service on behalf of users. Each client has secret private keys used to authenticate with Authress. To use service clients created through other mechanisms, skip creating a client and create access records with the client identifier.         <br><span class=\"badge badge-outline-secondary\">CREATE: Authress:ServiceClients</span> or <span class=\"badge badge-outline-secondary\">RESOURCE CLAIM: Authress:ServiceClients</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new AuthressClient\Api\ServiceClientsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \AuthressClient\Model\Body8(); // \AuthressClient\Model\Body8 | 

try {
    $result = $apiInstance->createClient($body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ServiceClientsApi->createClient: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\AuthressClient\Model\Body8**](../Model/Body8.md)|  |

### Return type

[**\AuthressClient\Model\InlineResponse20012**](../Model/InlineResponse20012.md)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/links+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **deleteAccessKey**
> deleteAccessKey($client_id, $key_id)

Remove an access key for a client.

Deletes an access key for a client prevent it from being used to authenticate with Authress.         <br><span class=\"badge badge-outline-secondary\">UPDATE: Authress:ServiceClients/{clientId}/access-keys/{keyId}</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new AuthressClient\Api\ServiceClientsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$client_id = "client_id_example"; // string | The unique identifier of the client.
$key_id = "key_id_example"; // string | The id of the access key to remove from the client.

try {
    $apiInstance->deleteAccessKey($client_id, $key_id);
} catch (Exception $e) {
    echo 'Exception when calling ServiceClientsApi->deleteAccessKey: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **client_id** | **string**| The unique identifier of the client. |
 **key_id** | **string**| The id of the access key to remove from the client. |

### Return type

void (empty response body)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **deleteClient**
> deleteClient($client_id)

Delete a client.

This deletes the service client.         <br><span class=\"badge badge-outline-secondary\">UPDATE: Authress:ServiceClients/{clientId}</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new AuthressClient\Api\ServiceClientsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$client_id = "client_id_example"; // string | The unique identifier for the client.

try {
    $apiInstance->deleteClient($client_id);
} catch (Exception $e) {
    echo 'Exception when calling ServiceClientsApi->deleteClient: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **client_id** | **string**| The unique identifier for the client. |

### Return type

void (empty response body)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getClient**
> \AuthressClient\Model\InlineResponse20012 getClient($client_id)

Get a client.

Returns all information related to client except for the private access keys.         <br><span class=\"badge badge-outline-secondary\">READ: Authress:ServiceClients/{clientId}</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new AuthressClient\Api\ServiceClientsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$client_id = "client_id_example"; // string | The unique identifier for the client.

try {
    $result = $apiInstance->getClient($client_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ServiceClientsApi->getClient: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **client_id** | **string**| The unique identifier for the client. |

### Return type

[**\AuthressClient\Model\InlineResponse20012**](../Model/InlineResponse20012.md)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/links+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getClients**
> \AuthressClient\Model\InlineResponse20011 getClients()

Get clients collection.

Returns all clients that the user has access to in the account.         <br><span class=\"badge badge-outline-secondary\">READ: Authress:ServiceClients/{clientId}</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new AuthressClient\Api\ServiceClientsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $result = $apiInstance->getClients();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ServiceClientsApi->getClients: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters
This endpoint does not need any parameter.

### Return type

[**\AuthressClient\Model\InlineResponse20011**](../Model/InlineResponse20011.md)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/links+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **requestAccessKey**
> \AuthressClient\Model\InlineResponse20013 requestAccessKey($client_id)

Request a new access key.

Create a new access key for the client so that a service can authenticate with Authress as that client. Using the client will allow delegation of permission checking of users.         <br><span class=\"badge badge-outline-secondary\">UPDATE: Authress:ServiceClients/{clientId}/access-keys</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new AuthressClient\Api\ServiceClientsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$client_id = "client_id_example"; // string | The unique identifier of the client.

try {
    $result = $apiInstance->requestAccessKey($client_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ServiceClientsApi->requestAccessKey: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **client_id** | **string**| The unique identifier of the client. |

### Return type

[**\AuthressClient\Model\InlineResponse20013**](../Model/InlineResponse20013.md)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/links+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateClient**
> \AuthressClient\Model\InlineResponse20012 updateClient($body, $client_id)

Update a client.

Updates a client information.         <br><span class=\"badge badge-outline-secondary\">UPDATE: Authress:ServiceClients/{clientId}</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new AuthressClient\Api\ServiceClientsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \AuthressClient\Model\Body9(); // \AuthressClient\Model\Body9 | 
$client_id = "client_id_example"; // string | The unique identifier for the client.

try {
    $result = $apiInstance->updateClient($body, $client_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ServiceClientsApi->updateClient: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\AuthressClient\Model\Body9**](../Model/Body9.md)|  |
 **client_id** | **string**| The unique identifier for the client. |

### Return type

[**\AuthressClient\Model\InlineResponse20012**](../Model/InlineResponse20012.md)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/links+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

