<?php
/**
 * UserPermissionsApi
 *
 * @category Class
 *
 * @author   Authress Developers
 *
 * @link     https://authress.io/app/#/api
 */

namespace AuthressSdk\Api;

use AuthressSdk\ApiException;
use AuthressSdk\AuthressClient;
use AuthressSdk\HeaderSelector;
use AuthressSdk\Model\PermissionResponse;
use AuthressSdk\Model\UserResources;
use AuthressSdk\ObjectSerializer;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Query;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Utils;
use InvalidArgumentException;
use RuntimeException;
use stdClass;

/**
 * UserPermissionsApi Class Doc Comment
 *
 * @category Class
 *
 * @author   Authress Developers
 *
 * @link     https://authress.io/app/#/api
 */
class UserPermissionsApi
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var AuthressClient
     */
    protected $config;

    /**
     * @var HeaderSelector
     */
    protected $headerSelector;

    /**
     * @param HeaderSelector $selector
     */
    public function __construct(AuthressClient $config, HeaderSelector $selector = null)
    {
        $this->client = new Client();
        $this->config = $config;
        $this->headerSelector = $selector ?: new HeaderSelector();
    }

    /**
     * @return AuthressClient
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Operation authorizeUser
     *
     * Check to see if a user has permissions to a resource.
     *
     * @param string $user_id      The user to check permissions on (required)
     * @param string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed, the resource must be a full path, and permissions are not inherited by sub-resources. (required)
     * @param string $permission   Permission to check, &#x27;*&#x27; and scoped permissions can also be checked here. (required)
     *
     * @return void
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function authorizeUser($user_id, $resource_uri, $permission)
    {
        $this->authorizeUserWithHttpInfo($user_id, $resource_uri, $permission);
    }

    /**
     * Operation authorizeUserWithHttpInfo
     *
     * Check to see if a user has permissions to a resource.
     *
     * @param string $user_id      The user to check permissions on (required)
     * @param string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed, the resource must be a full path, and permissions are not inherited by sub-resources. (required)
     * @param string $permission   Permission to check, &#x27;*&#x27; and scoped permissions can also be checked here. (required)
     *
     * @return array of null, HTTP status code, HTTP response headers (array of strings)
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function authorizeUserWithHttpInfo($user_id, $resource_uri, $permission)
    {
        $returnType = '';
        $request = $this->authorizeUserRequest($user_id, $resource_uri, $permission);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }

            return [null, $statusCode, $response->getHeaders()];
        } catch (ApiException $e) {
            throw $e;
        }
    }

    /**
     * Create request for operation 'authorizeUser'
     *
     * @param string $user_id      The user to check permissions on (required)
     * @param string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed, the resource must be a full path, and permissions are not inherited by sub-resources. (required)
     * @param string $permission   Permission to check, &#x27;*&#x27; and scoped permissions can also be checked here. (required)
     *
     * @return \GuzzleHttp\Psr7\Request
     *
     * @throws InvalidArgumentException
     */
    protected function authorizeUserRequest($user_id, $resource_uri, $permission)
    {
        // verify the required parameter 'user_id' is set
        if ($user_id === null || (is_array($user_id) && count($user_id) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $user_id when calling authorizeUser'
            );
        }
        // verify the required parameter 'resource_uri' is set
        if ($resource_uri === null || (is_array($resource_uri) && count($resource_uri) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $resource_uri when calling authorizeUser'
            );
        }
        // verify the required parameter 'permission' is set
        if ($permission === null || (is_array($permission) && count($permission) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $permission when calling authorizeUser'
            );
        }

        $resourcePath = '/v1/users/{userId}/resources/{resourceUri}/permissions/{permission}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // path params
        if ($user_id !== null) {
            $resourcePath = str_replace(
                '{' . 'userId' . '}',
                ObjectSerializer::toPathValue($user_id),
                $resourcePath
            );
        }
        // path params
        if ($resource_uri !== null) {
            $resourcePath = str_replace(
                '{' . 'resourceUri' . '}',
                ObjectSerializer::toPathValue($resource_uri),
                $resourcePath
            );
        }
        // path params
        if ($permission !== null) {
            $resourcePath = str_replace(
                '{' . 'permission' . '}',
                ObjectSerializer::toPathValue($permission),
                $resourcePath
            );
        }

        // body params
        $_tempBody = null;

        if ($multipart) {
            $headers = $this->headerSelector->selectHeadersForMultipart(
                []
            );
        } else {
            $headers = $this->headerSelector->selectHeaders(
                [],
                []
            );
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
            // $_tempBody is the method argument, if present
            $httpBody = $_tempBody;
            // \stdClass has no __toString(), so we should encode it manually
            if ($httpBody instanceof stdClass && $headers['Content-Type'] === 'application/json') {
                $httpBody = Utils::jsonEncode($httpBody);
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $multipartContents[] = [
                        'name' => $formParamName,
                        'contents' => $formParamValue
                    ];
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);
            } elseif ($headers['Content-Type'] === 'application/json') {
                $httpBody = Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = Query::build($formParams);
            }
        }

        // // this endpoint requires Bearer token
        if ($this->config->getAccessToken() !== null) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $query = Query::build($queryParams);
        return new Request(
            'GET',
            $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Create http client option
     *
     * @return array of http client options
     *
     * @throws RuntimeException on file opening failure
     */
    protected function createHttpClientOption()
    {
        $options = [];
        if ($this->config->getDebug()) {
            $options[RequestOptions::DEBUG] = fopen($this->config->getDebugFile(), 'a');
            if (!$options[RequestOptions::DEBUG]) {
                throw new RuntimeException('Failed to open the debug file: ' . $this->config->getDebugFile());
            }
        }

        return $options;
    }

    /**
     * Operation authorizeUserAsync
     *
     * Check to see if a user has permissions to a resource.
     *
     * @param string $user_id      The user to check permissions on (required)
     * @param string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed, the resource must be a full path, and permissions are not inherited by sub-resources. (required)
     * @param string $permission   Permission to check, &#x27;*&#x27; and scoped permissions can also be checked here. (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function authorizeUserAsync($user_id, $resource_uri, $permission)
    {
        return $this->authorizeUserAsyncWithHttpInfo($user_id, $resource_uri, $permission)
            ->then(
                static function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation authorizeUserAsyncWithHttpInfo
     *
     * Check to see if a user has permissions to a resource.
     *
     * @param string $user_id      The user to check permissions on (required)
     * @param string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed, the resource must be a full path, and permissions are not inherited by sub-resources. (required)
     * @param string $permission   Permission to check, &#x27;*&#x27; and scoped permissions can also be checked here. (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function authorizeUserAsyncWithHttpInfo($user_id, $resource_uri, $permission)
    {
        $returnType = '';
        $request = $this->authorizeUserRequest($user_id, $resource_uri, $permission);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                static function ($response) use ($returnType) {
                    return [null, $response->getStatusCode(), $response->getHeaders()];
                },
                static function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        $response->getBody()
                    );
                }
            );
    }

    /**
     * Operation getUserPermissionsForResource
     *
     * Get the permissions a user has to a resource.
     *
     * @param string $user_id      The user to check permissions on (required)
     * @param string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed. (required)
     *
     * @return PermissionResponse
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function getUserPermissionsForResource($user_id, $resource_uri)
    {
        [$response] = $this->getUserPermissionsForResourceWithHttpInfo($user_id, $resource_uri);
        return $response;
    }

    /**
     * Operation getUserPermissionsForResourceWithHttpInfo
     *
     * Get the permissions a user has to a resource.
     *
     * @param string $user_id      The user to check permissions on (required)
     * @param string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed. (required)
     *
     * @return array of \AuthressSdk\Model\PermissionResponse, HTTP status code, HTTP response headers (array of strings)
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function getUserPermissionsForResourceWithHttpInfo($user_id, $resource_uri)
    {
        $returnType = '\AuthressSdk\Model\PermissionResponse';
        $request = $this->getUserPermissionsForResourceRequest($user_id, $resource_uri);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }

            $responseBody = $response->getBody();
            if ($returnType === '\SplFileObject') {
                $content = $responseBody; //stream goes to serializer
            } else {
                $content = $responseBody->getContents();
                if (!in_array($returnType, ['string', 'integer', 'bool'])) {
                    $content = json_decode($content);
                }
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
            ];
        } catch (ApiException $e) {
            if ($e->getCode() == 200) {
                $data = ObjectSerializer::deserialize(
                    $e->getResponseBody(),
                    '\AuthressSdk\Model\PermissionResponse',
                    $e->getResponseHeaders()
                );
                $e->setResponseObject($data);
            }
            throw $e;
        }
    }

    /**
     * Create request for operation 'getUserPermissionsForResource'
     *
     * @param string $user_id      The user to check permissions on (required)
     * @param string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed. (required)
     *
     * @return \GuzzleHttp\Psr7\Request
     *
     * @throws InvalidArgumentException
     */
    protected function getUserPermissionsForResourceRequest($user_id, $resource_uri)
    {
        // verify the required parameter 'user_id' is set
        if ($user_id === null || (is_array($user_id) && count($user_id) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $user_id when calling getUserPermissionsForResource'
            );
        }
        // verify the required parameter 'resource_uri' is set
        if ($resource_uri === null || (is_array($resource_uri) && count($resource_uri) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $resource_uri when calling getUserPermissionsForResource'
            );
        }

        $resourcePath = '/v1/users/{userId}/resources/{resourceUri}/permissions';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // path params
        if ($user_id !== null) {
            $resourcePath = str_replace(
                '{' . 'userId' . '}',
                ObjectSerializer::toPathValue($user_id),
                $resourcePath
            );
        }
        // path params
        if ($resource_uri !== null) {
            $resourcePath = str_replace(
                '{' . 'resourceUri' . '}',
                ObjectSerializer::toPathValue($resource_uri),
                $resourcePath
            );
        }

        // body params
        $_tempBody = null;

        if ($multipart) {
            $headers = $this->headerSelector->selectHeadersForMultipart(
                ['application/links+json']
            );
        } else {
            $headers = $this->headerSelector->selectHeaders(
                ['application/links+json'],
                []
            );
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
            // $_tempBody is the method argument, if present
            $httpBody = $_tempBody;
            // \stdClass has no __toString(), so we should encode it manually
            if ($httpBody instanceof stdClass && $headers['Content-Type'] === 'application/json') {
                $httpBody = Utils::jsonEncode($httpBody);
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $multipartContents[] = [
                        'name' => $formParamName,
                        'contents' => $formParamValue
                    ];
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);
            } elseif ($headers['Content-Type'] === 'application/json') {
                $httpBody = Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = Query::build($formParams);
            }
        }

        // // this endpoint requires Bearer token
        if ($this->config->getAccessToken() !== null) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $query = Query::build($queryParams);
        return new Request(
            'GET',
            $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation getUserPermissionsForResourceAsync
     *
     * Get the permissions a user has to a resource.
     *
     * @param string $user_id      The user to check permissions on (required)
     * @param string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed. (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function getUserPermissionsForResourceAsync($user_id, $resource_uri)
    {
        return $this->getUserPermissionsForResourceAsyncWithHttpInfo($user_id, $resource_uri)
            ->then(
                static function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation getUserPermissionsForResourceAsyncWithHttpInfo
     *
     * Get the permissions a user has to a resource.
     *
     * @param string $user_id      The user to check permissions on (required)
     * @param string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed. (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function getUserPermissionsForResourceAsyncWithHttpInfo($user_id, $resource_uri)
    {
        $returnType = '\AuthressSdk\Model\PermissionResponse';
        $request = $this->getUserPermissionsForResourceRequest($user_id, $resource_uri);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                static function ($response) use ($returnType) {
                    $responseBody = $response->getBody();
                    if ($returnType === '\SplFileObject') {
                        $content = $responseBody; //stream goes to serializer
                    } else {
                        $content = $responseBody->getContents();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                static function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        $response->getBody()
                    );
                }
            );
    }

    /**
     * Operation getUserResources
     *
     * Get the resources a user has to permission to.
     *
     * @param string $user_id      The user to check permissions on (required)
     * @param string $resource_uri The top level uri path of a resource to query for. Will only match explicit or collection resource children. Will not partial match resource names. (optional, default to *)
     * @param string $permissions  Permission to check, &#x27;*&#x27; and scoped permissions can also be checked here. By default if the user has any permission explicitly to a resource, it will be included in the list. (optional)
     * @param int    $limit        Max number of results to return (optional, default to 20)
     * @param string $cursor       Continuation cursor for paging (will automatically be set) (optional)
     *
     * @return UserResources
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function getUserResources($user_id, $resource_uri = '*', $permissions = null, $limit = '20', $cursor = null)
    {
        [$response] = $this->getUserResourcesWithHttpInfo($user_id, $resource_uri, $permissions, $limit, $cursor);
        return $response;
    }

    /**
     * Operation getUserResourcesWithHttpInfo
     *
     * Get the resources a user has to permission to.
     *
     * @param string $user_id      The user to check permissions on (required)
     * @param string $resource_uri The top level uri path of a resource to query for. Will only match explicit or collection resource children. Will not partial match resource names. (optional, default to *)
     * @param string $permissions  Permission to check, &#x27;*&#x27; and scoped permissions can also be checked here. By default if the user has any permission explicitly to a resource, it will be included in the list. (optional)
     * @param int    $limit        Max number of results to return (optional, default to 20)
     * @param string $cursor       Continuation cursor for paging (will automatically be set) (optional)
     *
     * @return array of \AuthressSdk\Model\UserResources, HTTP status code, HTTP response headers (array of strings)
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function getUserResourcesWithHttpInfo(
        $user_id,
        $resource_uri = '*',
        $permissions = null,
        $limit = '20',
        $cursor = null
    ) {
        $returnType = '\AuthressSdk\Model\UserResources';
        $request = $this->getUserResourcesRequest($user_id, $resource_uri, $permissions, $limit, $cursor);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }

            $responseBody = $response->getBody();
            if ($returnType === '\SplFileObject') {
                $content = $responseBody; //stream goes to serializer
            } else {
                $content = $responseBody->getContents();
                if (!in_array($returnType, ['string', 'integer', 'bool'])) {
                    $content = json_decode($content);
                }
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
            ];
        } catch (ApiException $e) {
            if ($e->getCode() == 200) {
                $data = ObjectSerializer::deserialize(
                    $e->getResponseBody(),
                    '\AuthressSdk\Model\UserResources',
                    $e->getResponseHeaders()
                );
                $e->setResponseObject($data);
            }
            throw $e;
        }
    }

    /**
     * Create request for operation 'getUserResources'
     *
     * @param string $user_id      The user to check permissions on (required)
     * @param string $resource_uri The top level uri path of a resource to query for. Will only match explicit or collection resource children. Will not partial match resource names. (optional, default to *)
     * @param string $permissions  Permission to check, &#x27;*&#x27; and scoped permissions can also be checked here. By default if the user has any permission explicitly to a resource, it will be included in the list. (optional)
     * @param int    $limit        Max number of results to return (optional, default to 20)
     * @param string $cursor       Continuation cursor for paging (will automatically be set) (optional)
     *
     * @return \GuzzleHttp\Psr7\Request
     *
     * @throws InvalidArgumentException
     */
    protected function getUserResourcesRequest(
        $user_id,
        $resource_uri = '*',
        $permissions = null,
        $limit = '20',
        $cursor = null
    ) {
        // verify the required parameter 'user_id' is set
        if ($user_id === null || (is_array($user_id) && count($user_id) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $user_id when calling getUserResources'
            );
        }

        $resourcePath = '/v1/users/{userId}/resources';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // query params
        if ($resource_uri !== null) {
            $queryParams['resourceUri'] = ObjectSerializer::toQueryValue($resource_uri, null);
        }
        // query params
        if ($permissions !== null) {
            $queryParams['permissions'] = ObjectSerializer::toQueryValue($permissions, null);
        }
        // query params
        if ($limit !== null) {
            $queryParams['limit'] = ObjectSerializer::toQueryValue($limit, 'int32');
        }
        // query params
        if ($cursor !== null) {
            $queryParams['cursor'] = ObjectSerializer::toQueryValue($cursor, null);
        }

        // path params
        if ($user_id !== null) {
            $resourcePath = str_replace(
                '{' . 'userId' . '}',
                ObjectSerializer::toPathValue($user_id),
                $resourcePath
            );
        }

        // body params
        $_tempBody = null;

        if ($multipart) {
            $headers = $this->headerSelector->selectHeadersForMultipart(
                ['application/links+json']
            );
        } else {
            $headers = $this->headerSelector->selectHeaders(
                ['application/links+json'],
                []
            );
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
            // $_tempBody is the method argument, if present
            $httpBody = $_tempBody;
            // \stdClass has no __toString(), so we should encode it manually
            if ($httpBody instanceof stdClass && $headers['Content-Type'] === 'application/json') {
                $httpBody = Utils::jsonEncode($httpBody);
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $multipartContents[] = [
                        'name' => $formParamName,
                        'contents' => $formParamValue
                    ];
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);
            } elseif ($headers['Content-Type'] === 'application/json') {
                $httpBody = Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = Query::build($formParams);
            }
        }

        // // this endpoint requires Bearer token
        if ($this->config->getAccessToken() !== null) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $query = Query::build($queryParams);
        return new Request(
            'GET',
            $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation getUserResourcesAsync
     *
     * Get the resources a user has to permission to.
     *
     * @param string $user_id      The user to check permissions on (required)
     * @param string $resource_uri The top level uri path of a resource to query for. Will only match explicit or collection resource children. Will not partial match resource names. (optional, default to *)
     * @param string $permissions  Permission to check, &#x27;*&#x27; and scoped permissions can also be checked here. By default if the user has any permission explicitly to a resource, it will be included in the list. (optional)
     * @param int    $limit        Max number of results to return (optional, default to 20)
     * @param string $cursor       Continuation cursor for paging (will automatically be set) (optional)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function getUserResourcesAsync(
        $user_id,
        $resource_uri = '*',
        $permissions = null,
        $limit = '20',
        $cursor = null
    ) {
        return $this->getUserResourcesAsyncWithHttpInfo($user_id, $resource_uri, $permissions, $limit, $cursor)
            ->then(
                static function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation getUserResourcesAsyncWithHttpInfo
     *
     * Get the resources a user has to permission to.
     *
     * @param string $user_id      The user to check permissions on (required)
     * @param string $resource_uri The top level uri path of a resource to query for. Will only match explicit or collection resource children. Will not partial match resource names. (optional, default to *)
     * @param string $permissions  Permission to check, &#x27;*&#x27; and scoped permissions can also be checked here. By default if the user has any permission explicitly to a resource, it will be included in the list. (optional)
     * @param int    $limit        Max number of results to return (optional, default to 20)
     * @param string $cursor       Continuation cursor for paging (will automatically be set) (optional)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function getUserResourcesAsyncWithHttpInfo(
        $user_id,
        $resource_uri = '*',
        $permissions = null,
        $limit = '20',
        $cursor = null
    ) {
        $returnType = '\AuthressSdk\Model\UserResources';
        $request = $this->getUserResourcesRequest($user_id, $resource_uri, $permissions, $limit, $cursor);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                static function ($response) use ($returnType) {
                    $responseBody = $response->getBody();
                    if ($returnType === '\SplFileObject') {
                        $content = $responseBody; //stream goes to serializer
                    } else {
                        $content = $responseBody->getContents();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                static function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        $response->getBody()
                    );
                }
            );
    }
}
