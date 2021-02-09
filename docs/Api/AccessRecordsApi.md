# AuthressClient\AccessRecordsApi

All URIs are relative to */*

Method | HTTP request | Description
------------- | ------------- | -------------
[**createClaim**](AccessRecordsApi.md#createclaim) | **POST** /v1/claims | Claim a resource by an allowed user.
[**createInvite**](AccessRecordsApi.md#createinvite) | **POST** /v1/invites | Create a new invite.
[**createRecord**](AccessRecordsApi.md#createrecord) | **POST** /v1/records | Create a new access record.
[**deleteInvite**](AccessRecordsApi.md#deleteinvite) | **DELETE** /v1/invites/{inviteId} | Delete an invite.
[**deleteRecord**](AccessRecordsApi.md#deleterecord) | **DELETE** /v1/records/{recordId} | Deletes an access record.
[**getRecord**](AccessRecordsApi.md#getrecord) | **GET** /v1/records/{recordId} | Get an access record for the account.
[**getRecords**](AccessRecordsApi.md#getrecords) | **GET** /v1/records | Get all account records.
[**respondToInvite**](AccessRecordsApi.md#respondtoinvite) | **PATCH** /v1/invites/{inviteId} | Accept an invite.
[**updateRecord**](AccessRecordsApi.md#updaterecord) | **PUT** /v1/records/{recordId} | Update an access record.

# **createClaim**
> object createClaim($body)

Claim a resource by an allowed user.

Claim a resource by allowing a user to pick an identifier and receive admin access to that resource if it hasn't already been claimed. This only works for resources specifically marked as <strong>CLAIM</strong>. The result will be a new access record listing that user as the admin for this resource. The resourceUri will be appended to the collection resource uri using a '/' (forward slash) automatically.         <br><span class=\"badge badge-outline-secondary\">RESOURCE CLAIM: {resourceUri}</span> or <span class=\"badge badge-outline-secondary\">DELEGATE *: {resourceUri}</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


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
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\AuthressClient\Model\Body6**](../Model/Body6.md)|  |

### Return type

**object**

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/links+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createInvite**
> createInvite($body)

Create a new invite.

Invites are used to easily assign permissions to users that have not been created in your identity provider yet. Create the invite with an email address, and then when the user accepts the invite they will automatically get the permissions assigned here. Invites automatically expire after 7 days.         <br><span class=\"badge badge-outline-secondary\">GRANT: Existing Resource Permissions</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


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
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\AuthressClient\Model\Body3**](../Model/Body3.md)|  |

### Return type

void (empty response body)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createRecord**
> \AuthressClient\Model\InlineResponse2006 createRecord($body)

Create a new access record.

Specify user roles for specific resources. (Records have a maximum size of ~100KB)         <br><span class=\"badge badge-outline-secondary\">GRANT: Existing Resource Permissions</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


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
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\AuthressClient\Model\Body1**](../Model/Body1.md)|  |

### Return type

[**\AuthressClient\Model\InlineResponse2006**](../Model/InlineResponse2006.md)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/links+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **deleteInvite**
> deleteInvite($invite_id)

Delete an invite.

Deletes an invite.         <br><span class=\"badge badge-outline-secondary\">READ: Authress:UserPermissions/{userId}</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


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
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **invite_id** | **string**| The identifier of the invite. |

### Return type

void (empty response body)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **deleteRecord**
> deleteRecord($record_id)

Deletes an access record.

Remove an access record, removing associated permissions from all users in record. If a user has a permission from another record, that permission will not be removed. (Note: This disables the record by changing the status to <strong>DELETED</strong> but not completely remove the record for tracking purposes.           <br><span class=\"badge badge-outline-secondary\">UPDATE (or Admin): Authress:AccessRecords/{recordId}</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


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
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **record_id** | **string**| The identifier of the access record. |

### Return type

void (empty response body)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getRecord**
> \AuthressClient\Model\InlineResponse2006 getRecord($record_id)

Get an access record for the account.

Access records contain information assigning permissions to users for resources.         <br><span class=\"badge badge-outline-secondary\">READ (or Admin): Authress:AccessRecords/{recordId}</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


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
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **record_id** | **string**| The identifier of the access record. |

### Return type

[**\AuthressClient\Model\InlineResponse2006**](../Model/InlineResponse2006.md)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/links+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getRecords**
> \AuthressClient\Model\InlineResponse2005 getRecords($limit, $cursor, $filter, $status)

Get all account records.

<i class=\"far fa-money-bill-alt text-primary\"></i> <span class=\"text-primary\">Billable</span> Returns a paginated records list for the account. Only records the user has access to are returned.         <br><span class=\"badge badge-outline-secondary\">READ (or Admin): Authress:AccessRecords/{recordId}</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


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
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **limit** | **int**| Max number of results to return | [optional] [default to 20]
 **cursor** | **string**| Continuation cursor for paging (will automatically be set) | [optional]
 **filter** | **string**| Filter to search records by. This is a case insensitive search through every text field. | [optional]
 **status** | **string**| Filter records by their current status. | [optional]

### Return type

[**\AuthressClient\Model\InlineResponse2005**](../Model/InlineResponse2005.md)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/links+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **respondToInvite**
> \AuthressClient\Model\InlineResponse2007 respondToInvite($invite_id)

Accept an invite.

Accepts an invite by claiming this invite by this user. The user token used for this request will gain the permissions associated with the invite.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


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
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **invite_id** | **string**| The identifier of the invite. |

### Return type

[**\AuthressClient\Model\InlineResponse2007**](../Model/InlineResponse2007.md)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/links+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateRecord**
> \AuthressClient\Model\InlineResponse2006 updateRecord($body, $record_id)

Update an access record.

Updates an access record adding or removing user permissions to resources. (Records have a maximum size of ~100KB)         <br><span class=\"badge badge-outline-secondary\">UPDATE (or Admin): Authress:AccessRecords/{recordId}</span><span class=\"badge badge-outline-secondary\">GRANT: Existing Resource Permissions</span>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: oauth2
    $config = AuthressClient\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


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

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\AuthressClient\Model\Body2**](../Model/Body2.md)|  |
 **record_id** | **string**| The identifier of the access record. |

### Return type

[**\AuthressClient\Model\InlineResponse2006**](../Model/InlineResponse2006.md)

### Authorization

[oauth2](../../README.md#oauth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/links+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

