# AuthressClient\RolesApi

All URIs are relative to */*

Method | HTTP request | Description
------------- | ------------- | -------------
[**createRole**](RolesApi.md#createrole) | **POST** /v1/roles | Create a role.
[**deleteRole**](RolesApi.md#deleterole) | **DELETE** /v1/roles/{roleId} | Deletes a role.
[**getRole**](RolesApi.md#getrole) | **GET** /v1/roles/{roleId} | Get a role.
[**updateRole**](RolesApi.md#updaterole) | **PUT** /v1/roles/{roleId} | Update a role.

# **createRole**
> \AuthressClient\Model\InlineResponse2008 createRole($body)

Create a role.

Creates a role with permissions.         <br><span class=\"badge badge-outline-secondary\">CREATE: Authress:Roles</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new AuthressClient\Api\RolesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \AuthressClient\Model\Body4(); // \AuthressClient\Model\Body4 | 

try {
    $result = $apiInstance->createRole($body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RolesApi->createRole: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\AuthressClient\Model\Body4**](../Model/Body4.md)|  |

### Return type

[**\AuthressClient\Model\InlineResponse2008**](../Model/InlineResponse2008.md)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/links+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **deleteRole**
> deleteRole($role_id)

Deletes a role.

Remove a role. If a record references the role, that record will not be modified.         <br><span class=\"badge badge-outline-secondary\">UPDATE: Authress:Roles/{roleId}</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new AuthressClient\Api\RolesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$role_id = "role_id_example"; // string | The identifier of the role.

try {
    $apiInstance->deleteRole($role_id);
} catch (Exception $e) {
    echo 'Exception when calling RolesApi->deleteRole: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **role_id** | **string**| The identifier of the role. |

### Return type

void (empty response body)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getRole**
> \AuthressClient\Model\InlineResponse2008 getRole($role_id)

Get a role.

Roles contain a list of permissions that will be applied to any user or resource         <br><span class=\"badge badge-outline-secondary\">READ: Authress:Roles/{roleId}</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new AuthressClient\Api\RolesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$role_id = "role_id_example"; // string | The identifier of the role.

try {
    $result = $apiInstance->getRole($role_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RolesApi->getRole: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **role_id** | **string**| The identifier of the role. |

### Return type

[**\AuthressClient\Model\InlineResponse2008**](../Model/InlineResponse2008.md)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/links+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateRole**
> \AuthressClient\Model\InlineResponse2008 updateRole($body, $role_id)

Update a role.

Updates a role adding or removing permissions.         <br><span class=\"badge badge-outline-secondary\">UPDATE: Authress:Roles/{roleId}</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new AuthressClient\Api\RolesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \AuthressClient\Model\Body5(); // \AuthressClient\Model\Body5 | 
$role_id = "role_id_example"; // string | The identifier of the role.

try {
    $result = $apiInstance->updateRole($body, $role_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RolesApi->updateRole: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\AuthressClient\Model\Body5**](../Model/Body5.md)|  |
 **role_id** | **string**| The identifier of the role. |

### Return type

[**\AuthressClient\Model\InlineResponse2008**](../Model/InlineResponse2008.md)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/links+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

