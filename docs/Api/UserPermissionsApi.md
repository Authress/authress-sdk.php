# AuthressClient\UserPermissionsApi

All URIs are relative to */*

Method | HTTP request | Description
------------- | ------------- | -------------
[**authorizeUser**](UserPermissionsApi.md#authorizeuser) | **GET** /v1/users/{userId}/resources/{resourceUri}/permissions/{permission} | Check to see if a user has permissions to a resource.
[**getUserPermissionsForResource**](UserPermissionsApi.md#getuserpermissionsforresource) | **GET** /v1/users/{userId}/resources/{resourceUri}/permissions | Get the permissions a user has to a resource.
[**getUserResources**](UserPermissionsApi.md#getuserresources) | **GET** /v1/users/{userId}/resources | Get the resources a user has to permission to.

# **authorizeUser**
> authorizeUser($user_id, $resource_uri, $permission)

Check to see if a user has permissions to a resource.

<i class=\"far fa-money-bill-alt text-primary\"></i> <span class=\"text-primary\">Billable</span> Does the user have the specified permissions to the resource?         <br><span class=\"badge badge-outline-secondary\">READ: Authress:UserPermissions/{userId}</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new AuthressClient\Api\UserPermissionsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$user_id = "user_id_example"; // string | The user to check permissions on
$resource_uri = "resource_uri_example"; // string | The uri path of a resource to validate, must be URL encoded, uri segments are allowed, the resource must be a full path, and permissions are not inherited by sub-resources.
$permission = "permission_example"; // string | Permission to check, '*' and scoped permissions can also be checked here.

try {
    $apiInstance->authorizeUser($user_id, $resource_uri, $permission);
} catch (Exception $e) {
    echo 'Exception when calling UserPermissionsApi->authorizeUser: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **user_id** | **string**| The user to check permissions on |
 **resource_uri** | **string**| The uri path of a resource to validate, must be URL encoded, uri segments are allowed, the resource must be a full path, and permissions are not inherited by sub-resources. |
 **permission** | **string**| Permission to check, &#x27;*&#x27; and scoped permissions can also be checked here. |

### Return type

void (empty response body)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getUserPermissionsForResource**
> \AuthressClient\Model\InlineResponse2001 getUserPermissionsForResource($user_id, $resource_uri)

Get the permissions a user has to a resource.

<i class=\"far fa-money-bill-alt text-primary\"></i> <span class=\"text-primary\">Billable</span> Get a summary of the permissions a user has to a particular resource.         <br><span class=\"badge badge-outline-secondary\">READ: Authress:UserPermissions/{userId}</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new AuthressClient\Api\UserPermissionsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$user_id = "user_id_example"; // string | The user to check permissions on
$resource_uri = "resource_uri_example"; // string | The uri path of a resource to validate, must be URL encoded, uri segments are allowed.

try {
    $result = $apiInstance->getUserPermissionsForResource($user_id, $resource_uri);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling UserPermissionsApi->getUserPermissionsForResource: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **user_id** | **string**| The user to check permissions on |
 **resource_uri** | **string**| The uri path of a resource to validate, must be URL encoded, uri segments are allowed. |

### Return type

[**\AuthressClient\Model\InlineResponse2001**](../Model/InlineResponse2001.md)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/links+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getUserResources**
> \AuthressClient\Model\InlineResponse200 getUserResources($user_id, $resource_uri, $permissions, $limit, $cursor)

Get the resources a user has to permission to.

<i class=\"far fa-money-bill-alt text-primary\"></i> <span class=\"text-primary\">Billable</span> Get the users resources. This result is a list of resource uris that a user has an explicit permission to, a user with * access to all sub resources will return an empty list. To get a user's list of resources in this cases, it is recommended to also check explicit access to the collection resource, using the <strong>authorizeUser</strong> endpoint. In the case that the user only has access to a subset of resources in a collection, the list will be paginated.         <br><span class=\"badge badge-outline-secondary\">READ: Authress:UserPermissions/{userId}</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new AuthressClient\Api\UserPermissionsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$user_id = "user_id_example"; // string | The user to check permissions on
$resource_uri = "*"; // string | The top level uri path of a resource to query for. Will only match explicit or collection resource children. Will not partial match resource names.
$permissions = "permissions_example"; // string | Permission to check, '*' and scoped permissions can also be checked here. By default if the user has any permission explicitly to a resource, it will be included in the list.
$limit = 20; // int | Max number of results to return
$cursor = "cursor_example"; // string | Continuation cursor for paging (will automatically be set)

try {
    $result = $apiInstance->getUserResources($user_id, $resource_uri, $permissions, $limit, $cursor);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling UserPermissionsApi->getUserResources: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **user_id** | **string**| The user to check permissions on |
 **resource_uri** | **string**| The top level uri path of a resource to query for. Will only match explicit or collection resource children. Will not partial match resource names. | [optional] [default to *]
 **permissions** | **string**| Permission to check, &#x27;*&#x27; and scoped permissions can also be checked here. By default if the user has any permission explicitly to a resource, it will be included in the list. | [optional]
 **limit** | **int**| Max number of results to return | [optional] [default to 20]
 **cursor** | **string**| Continuation cursor for paging (will automatically be set) | [optional]

### Return type

[**\AuthressClient\Model\InlineResponse200**](../Model/InlineResponse200.md)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/links+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

