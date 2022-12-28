<?php
/**
 * ResourcePermissionsApi

 * @category Class
 * @package  AuthressSdk
 * @author   Authress Developers
 * @link     https://authress.io/app/#/api
 */


namespace AuthressSdk\Api;

use AuthressSdk\Model\ResourcePermission;
use AuthressSdk\Model\ResourcePermissionCollection;
use AuthressSdk\Model\ResourceUsersCollection;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Query;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use AuthressSdk\ApiException;
use AuthressSdk\AuthressClient;
use AuthressSdk\HeaderSelector;
use AuthressSdk\ObjectSerializer;
use GuzzleHttp\Utils;
use InvalidArgumentException;
use RuntimeException;
use stdClass;

/**
 * ResourcePermissionsApi Class Doc Comment
 *
 * @category Class
 * @package  AuthressSdk
 * @author   Authress Developers
 * @link     https://authress.io/app/#/api
 */
class ResourcePermissionsApi
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
     * @param AuthressClient   $config
     * @param HeaderSelector  $selector
     */
    public function __construct(AuthressClient $config = null, HeaderSelector $selector = null) {
        $this->client = new Client();
        $this->config = $config ?: new AuthressClient();
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
     * Operation getResourcePermissions
     *
     * Get a resource permissions object.
     *
     * @param  string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed. (required)
     *
     * @throws \AuthressSdk\ApiException on non-2xx response
     * @throws InvalidArgumentException
     * @return ResourcePermission
     */
    public function getResourcePermissions($resource_uri)
    {
        list($response) = $this->getResourcePermissionsWithHttpInfo($resource_uri);
        return $response;
    }

    /**
     * Operation getResourcePermissionsWithHttpInfo
     *
     * Get a resource permissions object.
     *
     * @param  string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed. (required)
     *
     * @throws \AuthressSdk\ApiException on non-2xx response
     * @throws InvalidArgumentException
     * @return array of \AuthressSdk\Model\ResourcePermission, HTTP status code, HTTP response headers (array of strings)
     */
    public function getResourcePermissionsWithHttpInfo($resource_uri)
    {
        $returnType = '\AuthressSdk\Model\ResourcePermission';
        $request = $this->getResourcePermissionsRequest($resource_uri);

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
                if (!in_array($returnType, ['string','integer','bool'])) {
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
                    '\AuthressSdk\Model\ResourcePermission',
                    $e->getResponseHeaders()
                );
                $e->setResponseObject($data);
            }
            throw $e;
        }
    }

    /**
     * Operation getResourcePermissionsAsync
     *
     * Get a resource permissions object.
     *
     * @param  string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed. (required)
     *
     * @throws InvalidArgumentException
     * @return PromiseInterface
     */
    public function getResourcePermissionsAsync($resource_uri)
    {
        return $this->getResourcePermissionsAsyncWithHttpInfo($resource_uri)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation getResourcePermissionsAsyncWithHttpInfo
     *
     * Get a resource permissions object.
     *
     * @param  string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed. (required)
     *
     * @throws InvalidArgumentException
     * @return PromiseInterface
     */
    public function getResourcePermissionsAsyncWithHttpInfo($resource_uri)
    {
        $returnType = '\AuthressSdk\Model\ResourcePermission';
        $request = $this->getResourcePermissionsRequest($resource_uri);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
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
                function ($exception) {
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
     * Create request for operation 'getResourcePermissions'
     *
     * @param  string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed. (required)
     *
     * @throws InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    protected function getResourcePermissionsRequest($resource_uri)
    {
        // verify the required parameter 'resource_uri' is set
        if ($resource_uri === null || (is_array($resource_uri) && count($resource_uri) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $resource_uri when calling getResourcePermissions'
            );
        }

        $resourcePath = '/v1/resources/{resourceUri}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


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
     * Operation getResourceUsers
     *
     * Get the users that have explicit access to this resource.
     *
     * @param  string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed. (required)
     * @param  int $limit Max number of results to return (optional, default to 20)
     * @param  string $cursor Continuation cursor for paging (will automatically be set) (optional)
     *
     * @throws \AuthressSdk\ApiException on non-2xx response
     * @throws InvalidArgumentException
     * @return ResourceUsersCollection
     */
    public function getResourceUsers($resource_uri, $limit = '20', $cursor = null)
    {
        list($response) = $this->getResourceUsersWithHttpInfo($resource_uri, $limit, $cursor);
        return $response;
    }

    /**
     * Operation getResourceUsersWithHttpInfo
     *
     * Get the users that have explicit access to this resource.
     *
     * @param  string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed. (required)
     * @param  int $limit Max number of results to return (optional, default to 20)
     * @param  string $cursor Continuation cursor for paging (will automatically be set) (optional)
     *
     * @throws \AuthressSdk\ApiException on non-2xx response
     * @throws InvalidArgumentException
     * @return array of \AuthressSdk\Model\ResourceUsersCollection, HTTP status code, HTTP response headers (array of strings)
     */
    public function getResourceUsersWithHttpInfo($resource_uri, $limit = '20', $cursor = null)
    {
        $returnType = '\AuthressSdk\Model\ResourceUsersCollection';
        $request = $this->getResourceUsersRequest($resource_uri, $limit, $cursor);

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
                if (!in_array($returnType, ['string','integer','bool'])) {
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
                    '\AuthressSdk\Model\ResourceUsersCollection',
                    $e->getResponseHeaders()
                );
                $e->setResponseObject($data);
            }
            throw $e;
        }
    }

    /**
     * Operation getResourceUsersAsync
     *
     * Get the users that have explicit access to this resource.
     *
     * @param  string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed. (required)
     * @param  int $limit Max number of results to return (optional, default to 20)
     * @param  string $cursor Continuation cursor for paging (will automatically be set) (optional)
     *
     * @throws InvalidArgumentException
     * @return PromiseInterface
     */
    public function getResourceUsersAsync($resource_uri, $limit = '20', $cursor = null)
    {
        return $this->getResourceUsersAsyncWithHttpInfo($resource_uri, $limit, $cursor)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation getResourceUsersAsyncWithHttpInfo
     *
     * Get the users that have explicit access to this resource.
     *
     * @param  string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed. (required)
     * @param  int $limit Max number of results to return (optional, default to 20)
     * @param  string $cursor Continuation cursor for paging (will automatically be set) (optional)
     *
     * @throws InvalidArgumentException
     * @return PromiseInterface
     */
    public function getResourceUsersAsyncWithHttpInfo($resource_uri, $limit = '20', $cursor = null)
    {
        $returnType = '\AuthressSdk\Model\ResourceUsersCollection';
        $request = $this->getResourceUsersRequest($resource_uri, $limit, $cursor);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
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
                function ($exception) {
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
     * Create request for operation 'getResourceUsers'
     *
     * @param  string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed. (required)
     * @param  int $limit Max number of results to return (optional, default to 20)
     * @param  string $cursor Continuation cursor for paging (will automatically be set) (optional)
     *
     * @throws InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    protected function getResourceUsersRequest($resource_uri, $limit = '20', $cursor = null)
    {
        // verify the required parameter 'resource_uri' is set
        if ($resource_uri === null || (is_array($resource_uri) && count($resource_uri) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $resource_uri when calling getResourceUsers'
            );
        }

        $resourcePath = '/v1/resources/{resourceUri}/users';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // query params
        if ($limit !== null) {
            $queryParams['limit'] = ObjectSerializer::toQueryValue($limit, 'int32');
        }
        // query params
        if ($cursor !== null) {
            $queryParams['cursor'] = ObjectSerializer::toQueryValue($cursor, null);
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
     * Operation getResources
     *
     * List resource configurations.
     *
     *
     * @throws \AuthressSdk\ApiException on non-2xx response
     * @throws InvalidArgumentException
     * @return ResourcePermissionCollection
     */
    public function getResources()
    {
        list($response) = $this->getResourcesWithHttpInfo();
        return $response;
    }

    /**
     * Operation getResourcesWithHttpInfo
     *
     * List resource configurations.
     *
     *
     * @throws \AuthressSdk\ApiException on non-2xx response
     * @throws InvalidArgumentException
     * @return array of \AuthressSdk\Model\ResourcePermissionCollection, HTTP status code, HTTP response headers (array of strings)
     */
    public function getResourcesWithHttpInfo()
    {
        $returnType = '\AuthressSdk\Model\ResourcePermissionCollection';
        $request = $this->getResourcesRequest();

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
                if (!in_array($returnType, ['string','integer','bool'])) {
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
                    '\AuthressSdk\Model\ResourcePermissionCollection',
                    $e->getResponseHeaders()
                );
                $e->setResponseObject($data);
            }
            throw $e;
        }
    }

    /**
     * Operation getResourcesAsync
     *
     * List resource configurations.
     *
     *
     * @throws InvalidArgumentException
     * @return PromiseInterface
     */
    public function getResourcesAsync()
    {
        return $this->getResourcesAsyncWithHttpInfo()
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation getResourcesAsyncWithHttpInfo
     *
     * List resource configurations.
     *
     *
     * @throws InvalidArgumentException
     * @return PromiseInterface
     */
    public function getResourcesAsyncWithHttpInfo()
    {
        $returnType = '\AuthressSdk\Model\ResourcePermissionCollection';
        $request = $this->getResourcesRequest();

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
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
                function ($exception) {
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
     * Create request for operation 'getResources'
     *
     *
     * @throws InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    protected function getResourcesRequest()
    {

        $resourcePath = '/v1/resources';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;



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
     * Operation updateResourcePermissions
     *
     * Update a resource permissions object.
     *
     * @param  ResourcePermission $body The contents of the permission to set on the resource. Overwrites existing data. (required)
     * @param  string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed. (required)
     *
     * @throws \AuthressSdk\ApiException on non-2xx response
     * @throws InvalidArgumentException
     * @return void
     */
    public function updateResourcePermissions($body, $resource_uri)
    {
        $this->updateResourcePermissionsWithHttpInfo($body, $resource_uri);
    }

    /**
     * Operation updateResourcePermissionsWithHttpInfo
     *
     * Update a resource permissions object.
     *
     * @param  ResourcePermission $body The contents of the permission to set on the resource. Overwrites existing data. (required)
     * @param  string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed. (required)
     *
     * @throws \AuthressSdk\ApiException on non-2xx response
     * @throws InvalidArgumentException
     * @return array of null, HTTP status code, HTTP response headers (array of strings)
     */
    public function updateResourcePermissionsWithHttpInfo($body, $resource_uri)
    {
        $returnType = '';
        $request = $this->updateResourcePermissionsRequest($body, $resource_uri);

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
            switch ($e->getCode()) {
            }
            throw $e;
        }
    }

    /**
     * Operation updateResourcePermissionsAsync
     *
     * Update a resource permissions object.
     *
     * @param  ResourcePermission $body The contents of the permission to set on the resource. Overwrites existing data. (required)
     * @param  string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed. (required)
     *
     * @throws InvalidArgumentException
     * @return PromiseInterface
     */
    public function updateResourcePermissionsAsync($body, $resource_uri)
    {
        return $this->updateResourcePermissionsAsyncWithHttpInfo($body, $resource_uri)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation updateResourcePermissionsAsyncWithHttpInfo
     *
     * Update a resource permissions object.
     *
     * @param  ResourcePermission $body The contents of the permission to set on the resource. Overwrites existing data. (required)
     * @param  string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed. (required)
     *
     * @throws InvalidArgumentException
     * @return PromiseInterface
     */
    public function updateResourcePermissionsAsyncWithHttpInfo($body, $resource_uri)
    {
        $returnType = '';
        $request = $this->updateResourcePermissionsRequest($body, $resource_uri);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    return [null, $response->getStatusCode(), $response->getHeaders()];
                },
                function ($exception) {
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
     * Create request for operation 'updateResourcePermissions'
     *
     * @param  ResourcePermission $body The contents of the permission to set on the resource. Overwrites existing data. (required)
     * @param  string $resource_uri The uri path of a resource to validate, must be URL encoded, uri segments are allowed. (required)
     *
     * @throws InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    protected function updateResourcePermissionsRequest($body, $resource_uri)
    {
        // verify the required parameter 'body' is set
        if ($body === null || (is_array($body) && count($body) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $body when calling updateResourcePermissions'
            );
        }
        // verify the required parameter 'resource_uri' is set
        if ($resource_uri === null || (is_array($resource_uri) && count($resource_uri) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $resource_uri when calling updateResourcePermissions'
            );
        }

        $resourcePath = '/v1/resources/{resourceUri}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


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
        if (isset($body)) {
            $_tempBody = $body;
        }

        if ($multipart) {
            $headers = $this->headerSelector->selectHeadersForMultipart(
                []
            );
        } else {
            $headers = $this->headerSelector->selectHeaders(
                [],
                ['application/json']
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
            'PUT',
            $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Create http client option
     *
     * @throws RuntimeException on file opening failure
     * @return array of http client options
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
}
