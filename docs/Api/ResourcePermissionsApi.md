# AuthressClient\ResourcePermissionsApi

All URIs are relative to */*

Method | HTTP request | Description
------------- | ------------- | -------------
[**getResourcePermissions**](ResourcePermissionsApi.md#getresourcepermissions) | **GET** /v1/resources/{resourceUri} | Get a resource permissions object.
[**getResourceUsers**](ResourcePermissionsApi.md#getresourceusers) | **GET** /v1/resources/{resourceUri}/users | Get the users that have explicit access to this resource.
[**getResources**](ResourcePermissionsApi.md#getresources) | **GET** /v1/resources | List resource configurations.
[**updateResourcePermissions**](ResourcePermissionsApi.md#updateresourcepermissions) | **PUT** /v1/resources/{resourceUri} | Update a resource permissions object.

# **getResourcePermissions**
> \AuthressClient\Model\InlineResponse2003 getResourcePermissions($resource_uri)

Get a resource permissions object.

Permissions can be set globally at a resource level. This will apply to all users in an account.         <br><span class=\"badge badge-outline-secondary\">GRANT *: Authress:ResourcePermissions/{resourceUri}</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new AuthressClient\Api\ResourcePermissionsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$resource_uri = "resource_uri_example"; // string | The uri path of a resource to validate, must be URL encoded, uri segments are allowed.

try {
    $result = $apiInstance->getResourcePermissions($resource_uri);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ResourcePermissionsApi->getResourcePermissions: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **resource_uri** | **string**| The uri path of a resource to validate, must be URL encoded, uri segments are allowed. |

### Return type

[**\AuthressClient\Model\InlineResponse2003**](../Model/InlineResponse2003.md)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/links+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getResourceUsers**
> \AuthressClient\Model\InlineResponse2004 getResourceUsers($resource_uri, $limit, $cursor)

Get the users that have explicit access to this resource.

<i class=\"far fa-money-bill-alt text-primary\"></i> <span class=\"text-primary\">Billable</span> Get the resource users. This result is a list of users that have some permission to the resource. Users with access to higher level resources nor users with access only to a sub-resource, will not be returned in this result. In the case that the resource has multiple users, the list will be paginated.         <br><span class=\"badge badge-outline-secondary\">READ: Authress:UserPermissions/{userId}</span><span class=\"badge badge-outline-secondary\">GRANT *: Authress:ResourcePermissions/{resourceUri}</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new AuthressClient\Api\ResourcePermissionsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$resource_uri = "resource_uri_example"; // string | The uri path of a resource to validate, must be URL encoded, uri segments are allowed.
$limit = 20; // int | Max number of results to return
$cursor = "cursor_example"; // string | Continuation cursor for paging (will automatically be set)

try {
    $result = $apiInstance->getResourceUsers($resource_uri, $limit, $cursor);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ResourcePermissionsApi->getResourceUsers: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **resource_uri** | **string**| The uri path of a resource to validate, must be URL encoded, uri segments are allowed. |
 **limit** | **int**| Max number of results to return | [optional] [default to 20]
 **cursor** | **string**| Continuation cursor for paging (will automatically be set) | [optional]

### Return type

[**\AuthressClient\Model\InlineResponse2004**](../Model/InlineResponse2004.md)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/links+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getResources**
> \AuthressClient\Model\InlineResponse2002 getResources()

List resource configurations.

Permissions can be set globally at a resource level. Lists any resources with a globally set resource policy.         <br><span class=\"badge badge-outline-secondary\">READ: Authress:ResourcePermissions</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new AuthressClient\Api\ResourcePermissionsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $result = $apiInstance->getResources();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ResourcePermissionsApi->getResources: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters
This endpoint does not need any parameter.

### Return type

[**\AuthressClient\Model\InlineResponse2002**](../Model/InlineResponse2002.md)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/links+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateResourcePermissions**
> updateResourcePermissions($body, $resource_uri)

Update a resource permissions object.

Updates the global permissions on a resource. This applies to all users in an account.         <br><span class=\"badge badge-outline-secondary\">GRANT *: Authress:ResourcePermissions/{resourceUri}</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new AuthressClient\Api\ResourcePermissionsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \AuthressClient\Model\Body(); // \AuthressClient\Model\Body | The contents of the permission to set on the resource. Overwrites existing data.
$resource_uri = "resource_uri_example"; // string | The uri path of a resource to validate, must be URL encoded, uri segments are allowed.

try {
    $apiInstance->updateResourcePermissions($body, $resource_uri);
} catch (Exception $e) {
    echo 'Exception when calling ResourcePermissionsApi->updateResourcePermissions: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\AuthressClient\Model\Body**](../Model/Body.md)| The contents of the permission to set on the resource. Overwrites existing data. |
 **resource_uri** | **string**| The uri path of a resource to validate, must be URL encoded, uri segments are allowed. |

### Return type

void (empty response body)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

